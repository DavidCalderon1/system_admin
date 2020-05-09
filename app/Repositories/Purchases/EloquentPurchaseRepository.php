<?php

namespace App\Repositories\Purchases;

use App\Models\Purchase;
use App\Repositories\Purchases\Interfaces\PurchaseRepositoryInterface;
use Illuminate\Http\UploadedFile;


/**
 * Class EloquentPurchaseRepository
 * @package App\Repositories\Purchases
 */
class EloquentPurchaseRepository implements PurchaseRepositoryInterface
{
    /**
     * @var Purchase
     */
    protected $purchase;

    /**
     * EloquentPurchaseRepository constructor.
     * @param Purchase $purchase
     */
    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * @param int $perPage
     * @param array $filers
     * @return array
     */
    public function getPagination(int $perPage, array $filers = []): array
    {
        $purchases = $this->purchase->with(['purchaseProducts']);

        if (!empty($filers['consecutive'])) {

            $invoiceNumberData = explode('-', $filers['consecutive']);

            if (count($invoiceNumberData) == 2) {
                $purchases->where('prefix', "LIKE", "%{$invoiceNumberData[0]}%");
                $purchases->where('consecutive', "LIKE", "%{$invoiceNumberData[1]}%");
            } else {
                $purchases->where('prefix', "LIKE", "%{$invoiceNumberData[0]}%");
                $purchases->orWhere('consecutive', "LIKE", "%{$invoiceNumberData[0]}%");
            }
        } elseif (!empty($filers['provider_name'])) {
            $purchases->orWhere('client_name', "LIKE", "%{$filers['provider_name']}%");
        } elseif (!empty($filers['status'])) {
            $purchases->orWhere('status', "LIKE", "%{$filers['status']}%");
        }

        return $purchases->paginate($perPage)->toArray();
    }

    /**
     * @param $purchaseId
     * @return array
     */
    public function get($purchaseId): array
    {
        $purchase = $this->purchase->with(['purchaseProducts', 'purchasePayments'])->where('id', $purchaseId)->first();

        return (!empty($purchase)) ? $purchase->toArray() : [];
    }

    /**
     * @param array $data
     * @return Purchase
     */
    public function create(array $data): Purchase
    {
        $purchaseNumber = $data['prefix'] . '-' . $data['consecutive'];

        return $this->purchase->create([
            'provider_id' => $data['provider_id'],
            'prefix' => $data['prefix'],
            'consecutive' => $data['consecutive'],
            'provider_invoice_number' => $data['provider_invoice_number'],
            'provider_name' => $data['provider_name'],
            'provider_last_name' => $data['provider_last_name'],
            'provider_identity_number' => $data['provider_identity_number'],
            'provider_identity_type' => $data['provider_identity_type'],
            'provider_address' => $data['provider_address'],
            'provider_phone_number' => $data['provider_phone_number'],
            'provider_location' => $data['provider_location'],
            'description' => $data['description'],
            'status' => $data['status'],
            'include_taxes' => $data['include_taxes'],
            'date' => $data['date'],
            'file' => (!empty($data['file']) && $data['file'] instanceof UploadedFile)
                ? $data['file']->storePubliclyAs('public/purchases', $purchaseNumber . '.' . $data['file']->getClientOriginalExtension())
                : '',
        ]);
    }

    /**
     * @return int
     */
    public function getLastConsecutive(): int
    {
        $purchase = $this->purchase->orderBy('consecutive', 'desc')->first();

        if (empty($purchase)) {
            return 0;
        }

        return $purchase->consecutive;
    }

    /**
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function changeStatus(int $id, string $status): bool
    {
        return $this->purchase->where('id', $id)->update([
            'status' => $status
        ]);
    }
}

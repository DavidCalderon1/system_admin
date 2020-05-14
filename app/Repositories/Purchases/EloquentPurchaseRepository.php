<?php

namespace App\Repositories\Purchases;

use App\Models\Purchase;
use App\Repositories\Purchases\Interfaces\PurchaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;


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
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return LengthAwarePaginator
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue = ''): LengthAwarePaginator
    {
        if ($orderBy == 'invoice_number') {
            $orderBy = 'consecutive';
        }

        $purchases = $this->purchase->with(['purchaseProducts'])
            ->where(DB::raw("CONCAT(`prefix`, '-', `consecutive`)"), 'LIKE', "%{$searchValue}%")
            ->orWhere('provider_name', "LIKE", "%{$searchValue}%")
            ->orWhere('provider_invoice_number', "LIKE", "%{$searchValue}%")
            ->orWhere('status', "LIKE", "%{$searchValue}%")
            ->orWhere('date', "LIKE", "%{$searchValue}%")
            ->orderBy($orderBy, $orderByDir);

        return $purchases->paginate($length);
    }

    /**
     * @param $purchaseId
     * @return array
     */
    public function get($purchaseId): array
    {
        $purchase = $this->purchase->with(['purchaseProducts', 'purchasePayments', 'purchaseProducts.product.warehouses'])
            ->where('id', $purchaseId)
            ->first();

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
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $purchaseNumber = $data['prefix_consecutive'];

        return $this->purchase->where('id', $id)->update([
            'provider_id' => $data['provider_id'],
            'provider_invoice_number' => $data['provider_invoice_number'],
            'provider_name' => $data['provider_name'],
            'provider_identity_number' => $data['provider_identity_number'],
            'provider_identity_type' => $data['provider_identity_type'],
            'provider_address' => $data['provider_address'],
            'provider_phone_number' => $data['provider_phone_number'],
            'provider_location' => $data['provider_location'],
            'description' => $data['description'],
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

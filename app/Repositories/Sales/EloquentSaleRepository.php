<?php

namespace App\Repositories\Sales;

use App\Models\Sale;
use App\Repositories\Sales\Interfaces\SaleRepositoryInterface;
use Illuminate\Http\UploadedFile;


/**
 * Class EloquentInvoiceRepository
 * @package App\Repositories
 */
class EloquentSaleRepository implements SaleRepositoryInterface
{
    /**
     * @var Sale
     */
    protected $sale;

    /**
     * EloquentSaleRepository constructor.
     * @param Sale $sale
     */
    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    /**
     * @param int $perPage
     * @param array $filers
     * @return array
     */
    public function getPagination(int $perPage, array $filers = []): array
    {
        $sales = $this->sale->with(['saleProducts']);

        if (!empty($filers['consecutive'])) {

            $invoiceNumberData = explode('-', $filers['consecutive']);

            if (count($invoiceNumberData) == 2) {
                $sales->where('prefix', "LIKE", "%{$invoiceNumberData[0]}%");
                $sales->where('consecutive', "LIKE", "%{$invoiceNumberData[1]}%");
            } else {
                $sales->where('prefix', "LIKE", "%{$invoiceNumberData[0]}%");
                $sales->orWhere('consecutive', "LIKE", "%{$invoiceNumberData[0]}%");
            }
        } elseif (!empty($filers['client_name'])) {
            $sales->orWhere('client_name', "LIKE", "%{$filers['client_name']}%");
        } elseif (!empty($filers['status'])) {
            $sales->orWhere('status', "LIKE", "%{$filers['status']}%");
        }

        return $sales->paginate($perPage)->toArray();
    }

    /**
     * @param $saleId
     * @return array
     */
    public function get($saleId): array
    {
        $sale = $this->sale->with(['saleProducts', 'salePayments'])->where('id', $saleId)->first();

        return (!empty($sale)) ? $sale->toArray() : [];
    }

    /**
     * @param array $data
     * @return Sale
     */
    public function create(array $data): Sale
    {
        $invoiceNumber = $data['prefix'] . '-' . $data['consecutive'];

        return $this->sale->create([
            'client_id' => $data['client_id'],
            'prefix' => $data['prefix'],
            'consecutive' => $data['consecutive'],
            'client_name' => $data['client_name'],
            'client_last_name' => $data['client_last_name'],
            'client_identity_number' => $data['client_identity_number'],
            'client_identity_type' => $data['client_identity_type'],
            'client_contact' => $data['client_contact'],
            'seller_code' => $data['seller_code'],
            'date' => $data['date'],
            'description' => $data['description'],
            'status' => $data['status'],
            'file' => (!empty($data['file']) && $data['file'] instanceof UploadedFile)
                ? $data['file']->storePubliclyAs('public/sales', $invoiceNumber . '.' . $data['file']->getClientOriginalExtension())
                : '',
        ]);
    }

    /**
     * @return int
     */
    public function getLastConsecutive(): int
    {
        $sale = $this->sale->orderBy('consecutive', 'desc')->first();

        if (empty($sale)) {
            return 0;
        }

        return $sale->consecutive;
    }

    /**
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function changeStatus(int $id, string $status): bool
    {
        return $this->sale->where('id', $id)->update([
            'status' => $status
        ]);
    }
}

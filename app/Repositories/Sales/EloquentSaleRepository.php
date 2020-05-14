<?php

namespace App\Repositories\Sales;

use App\Models\Sale;
use App\Repositories\Sales\Interfaces\SaleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;


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
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return array
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue = ''): array
    {
        if ($orderBy == 'invoice_number') {
            $orderBy = 'consecutive';
        }

        $sales = $this->sale->with(['saleProducts'])
            ->where(DB::raw("CONCAT(`prefix`, '-', `consecutive`)"), 'LIKE', "%{$searchValue}%")
            ->orWhere('client_name', "LIKE", "%{$searchValue}%")
            ->orWhere('status', "LIKE", "%{$searchValue}%")
            ->orderBy($orderBy, $orderByDir);

        return $sales->paginate($length)->toArray();
    }

    /**
     * @param $saleId
     * @return array
     */
    public function get($saleId): array
    {
        $sale = $this->sale->with(['saleProducts', 'saleProducts.product.warehouses', 'salePayments', 'client'])
            ->where('id', $saleId)
            ->first();

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
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $saleNumber = $data['prefix_consecutive'];

        return $this->sale->where('id', $id)->update([
            'client_id' => $data['client_id'],
            'client_name' => $data['client_name'],
            'client_last_name' => $data['client_last_name'],
            'client_identity_number' => $data['client_identity_number'],
            'client_identity_type' => $data['client_identity_type'],
            'client_contact' => $data['client_contact'],
            'seller_code' => $data['seller_code'],
            'date' => $data['date'],
            'description' => $data['description'],
            'file' => (!empty($data['file']) && $data['file'] instanceof UploadedFile)
                ? $data['file']->storePubliclyAs('public/sales', $saleNumber . '.' . $data['file']->getClientOriginalExtension())
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

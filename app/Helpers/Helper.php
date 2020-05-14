<?php

use Carbon\Carbon;

if (!function_exists('getPaymentsTrans')) {

    /**
     * @param array $payments
     * @return array
     */
    function getPaymentsTrans(array $payments): array
    {
        return array_map(function ($payment) {
            return [
                'way_to_pay' => $payment['way_to_pay'],
                'way_to_pay_trans' => __('payments.' . $payment['way_to_pay']),
                'amount' => $payment['amount'],
                'amount_formatted' => numberFormat($payment['amount']),
                'method' => $payment['method'],
                'days_to_pay' => $payment['days_to_pay'],
                'credit_expiration_date' => Carbon::create($payment['credit_expiration_date'])->format('Y-m-d'),
                'date' => $payment['date'],
            ];
        }, $payments);
    }
}

if (!function_exists('numberFormat')) {

    /**
     * @param float $value
     * @return string
     */
    function numberFormat(float $value): string
    {
        return number_format($value, 2, ',', '.');
    }
}

if (!function_exists('getTotalPayments')) {

    /**
     * @param array $payments
     * @return float
     */
    function getTotalPayments(array $payments): float
    {
        $totalPayments = 0;

        array_walk($payments, function ($payment) use (&$totalPayments) {
            $totalPayments += $payment['amount'];
        });

        return round($totalPayments, 2);
    }
}

if (!function_exists('responseDataTable')) {

    /**
     * @param array $data
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param int $draw
     * @param string $searchValue
     * @return array
     */
    function responseDataTable(array $data, int $length, string $orderBy, string $orderByDir, int $draw, string $searchValue): array
    {
        return [
            'data' => $data['data'],
            'links' => [
                'first' => $data['first_page_url'],
                'last' => $data['last_page_url'],
                'next' => $data['next_page_url'],
                'prev' => $data['prev_page_url'],
            ],
            'meta' => [
                'current_page' => $data['current_page'],
                'from' => $data['from'],
                'last_page' => $data['last_page'],
                'path' => $data['path'],
                'per_page' => $data['per_page'],
                'to' => $data['to'],
                'total' => $data['total'],
            ],
            'payload' => [
                'column' => $orderBy,
                'dir' => $orderByDir,
                'draw' => $draw,
                'length' => $length,
                'page' => $data['current_page'],
                'search' => $searchValue,
            ]
        ];
    }
}

<?php
declare(strict_types=1);

namespace Acme\OrderSync\Api;

interface ErpApiClientInterface
{
    /**
     * Summary of sendOrderData
     * @param array $orderData
     * @return array
     */
    public function sendOrderData(array $orderData): array;
}
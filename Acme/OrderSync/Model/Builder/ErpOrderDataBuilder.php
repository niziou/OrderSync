<?php
declare(strict_types=1);

namespace Acme\OrderSync\Model\Builder;

use Magento\Sales\Api\Data\OrderInterface;

class ErpOrderDataBuilder implements OrderDataBuilderInterface
{
    public function build(OrderInterface $order): array
    {
        $orderData = [
            'order_id' => $order->getIncrementId(),
            'customer_email' => $order->getCustomerEmail(),
            'total' => $order->getGrandTotal(),
            'items' => []
        ];

        foreach ($order->getAllItems() as $item) {
            $orderData['items'][] = [
                'sku' => $item->getSku(),
                'qty' => $item->getQtyOrdered(),
                'price' => $item->getPrice()
            ];
        }

        return $orderData;
    }
}
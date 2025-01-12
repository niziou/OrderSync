<?php
declare(strict_types=1);

namespace Acme\OrderSync\Model\Facade;

use Magento\Sales\Api\OrderRepositoryInterface;
use Acme\OrderSync\Model\Builder\OrderDataBuilderInterface;
use Acme\OrderSync\Api\ErpApiClientInterface;
use Psr\Log\LoggerInterface;

class OrderSyncFacade
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderDataBuilderInterface $orderDataBuilder,
        private ErpApiClientInterface $erpApiClient,
        private LoggerInterface $logger
    ) {
    }

    public function syncOrder(int $orderId): bool
    {
        try {
            $order = $this->orderRepository->get($orderId);
            $orderData = $this->orderDataBuilder->build($order);

            $response = $this->erpApiClient->sendOrderData($orderData);

            if ($response['success'] === true) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            $this->logger->error(sprintf(
                'Error in OrderSyncFacade::syncOrder for orderId %d: %s',
                $orderId,
                $e->getMessage()
            ));

            return false;
        }
    }
}
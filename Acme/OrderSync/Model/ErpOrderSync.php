<?php
declare(strict_types=1);

namespace Acme\OrderSync\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Model\OrderRepositoryInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

class ErpOrderSync extends AbstractModel
{
    protected OrderRepository $orderRepository;
    protected Curl $curl;
    protected ScopeConfigInterface $scopeConfig;
    protected LoggerInterface $logger;
    
    public function __construct(
        OrderRepository $orderRepository,
        Curl $curl,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->curl = $curl;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    public function syncOrder($orderId)
    {
        try {
            $order = $this->orderRepository->get($orderId);
            
            // Get API configuration
            $apiUrl = $this->scopeConfig->getValue('acme_ordersync/api/url');
            $apiKey = $this->scopeConfig->getValue('acme_ordersync/api/key');
            
            // Prepare order data
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
            
            // Send to ERP
            $this->curl->addHeader('Authorization', 'Bearer ' . $apiKey);
            $this->curl->addHeader('Content-Type', 'application/json');
            $this->curl->post($apiUrl, json_encode($orderData));
            
            $response = $this->curl->getBody();
            
            if ($this->curl->getStatus() == 200) {
                return true;
            } else {
                $this->logger->error('ERP sync failed: ' . $response);
                return false;
            }
            
        } catch (\Exception $e) {
            $this->logger->error('Error syncing order: ' . $e->getMessage());
            return false;
        }
    }
}


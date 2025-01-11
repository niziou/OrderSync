<?php
declare(strict_types=1);

namespace Acme\OrderSync\Model\Htpp;

use Acme\OrderSync\Api\ErpApiClientInterface;
use Acme\OrderSync\Model\Config\OrdersyncConfigProvider;
use Psr\Log\LoggerInterface;

class CurlAdapter implements ErpApiClientInterface
{
    public function __construct(
        private Curl $curl,
        private OrdersyncConfigProvider $configProvider,
        private LoggerInterface $logger
    ) {}

    public function sendOrderData(array $orderData): array
    {
        // todo check if order data has store ID
        $url = $this->configProvider->getApiUrl();
        $apiKey = $this->configProvider->getApiKey();

        try {
            $this->curl->addHeader('Authorization', 'Bearer ' . $apiKey);
            $this->curl->addHeader('Content-Type', 'application/json');
            $this->curl->post($url, json_encode($orderData));

            $responseBody = $this->curl->getBody();
            $status = $this->curl->getStatus();

            if ($status === 200) {
                return [
                    'success' => true,
                    'response' => $responseBody
                ];
            } else {
                $this->logger->error(sprintf(
                    'Error sending to ERP. Status: %d, Body: %s',
                    $status,
                    $responseBody
                ));

                return [
                    'success' => false,
                    'response' => $responseBody
                ];
            }

        } catch (\LocalizedException $e) {
            $this->logger->error('CurlAdapter exception: ' . $e->getMessage());
            return [
                'success' => false,
                'response' => $e->getMessage()
            ];
        }
    }
}
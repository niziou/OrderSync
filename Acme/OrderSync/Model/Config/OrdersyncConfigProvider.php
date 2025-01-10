<?php
declare(strict_types=1);

namespace Acme\OrderSync\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class OrdersyncConfigProvider
{
    public const XML_PATH_API_URL = 'acme_ordersync/api/url';
    public const XML_PATH_API_KEY = 'acme_ordersync/api/key';

    public function __construct(
        private ScopeConfigInterface $scopeConfig
    ) {}

    public function getApiUrl(int|string $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_API_URL, $storeId);
    }


    public function getApiKey(int|string $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_API_KEY, $storeId);
    }
}
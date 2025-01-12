<?php
declare(strict_types=1);

namespace Acme\OrderSync\Model\Builder;

use Magento\Sales\Api\Data\OrderInterface;

interface OrderDataBuilderInterface
{
    public function build(OrderInterface $order) : array;
}
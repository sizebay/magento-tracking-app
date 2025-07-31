<?php
namespace Sizebay\SizebayTracker\Model\Data;

class OrderItemFactory
{
    private $objectManager;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(array $data = []): OrderItem
    {
        return $this->objectManager->create(OrderItem::class, $data);
    }
}

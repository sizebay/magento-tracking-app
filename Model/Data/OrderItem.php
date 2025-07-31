<?php
namespace Sizebay\SizebayTracker\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Sizebay\SizebayTracker\Api\Data\OrderItemInterface;

class OrderItem extends AbstractExtensibleModel implements OrderItemInterface
{
    public function getSku(): ?string
    {
        return $this->_getData('sku');
    }

    public function setSku(string $sku): OrderItemInterface
    {
        return $this->setData('sku', $sku);
    }

    public function getQuantity(): ?int
    {
        return $this->_getData('quantity');
    }

    public function setQuantity(int $qty): OrderItemInterface
    {
        return $this->setData('quantity', $qty);
    }

    public function getPrice(): ?float
    {
        return $this->_getData('price');
    }

    public function setPrice(float $price): OrderItemInterface
    {
        return $this->setData('price', $price);
    }

    public function getPermalink(): ?string
    {
        return $this->_getData('permalink');
    }

    public function setPermalink(string $permalink): OrderItemInterface
    {
        return $this->setData('permalink', $permalink);
    }

    public function getSize(): ?string
    {
        return $this->_getData('size');
    }

    public function setSize(string $size): OrderItemInterface
    {
        return $this->setData('size', $size);
    }

    public function getFeedProductId(): ?int
    {
        return $this->_getData('feed_product_id');
    }

    public function setFeedProductId(int $id): OrderItemInterface
    {
        return $this->setData('feed_product_id', $id);
    }
}

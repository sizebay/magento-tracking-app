<?php
namespace Sizebay\SizebayTracker\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Sizebay\SizebayTracker\Api\Data\OrderItemInterface;

class OrderItem extends AbstractExtensibleModel implements OrderItemInterface
{
    /**
     * @var string|null
     */
    private $sku;

    /**
     * @var int|null
     */
    private $quantity;

    /**
     * @var float|null
     */
    private $price;

    /**
     * @var string|null
     */
    private $permalink;

    /**
     * @var string|null
     */
    private $size;

    /**
     * @var int|null
     */
    private $feedProductId;

    /**
     * @inheritdoc
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @inheritdoc
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @inheritdoc
     */
    public function setQuantity($qty)
    {
        $this->quantity = $qty;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @inheritdoc
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @inheritdoc
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @inheritdoc
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFeedProductId()
    {
        return $this->feedProductId;
    }

    /**
     * @inheritdoc
     */
    public function setFeedProductId($id)
    {
        $this->feedProductId = $id;
        return $this;
    }
}

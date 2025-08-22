<?php
namespace Sizebay\SizebayTracker\Model\Data;

use Sizebay\SizebayTracker\Api\Data\OrderItemInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

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
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return OrderItemInterface
     */
    public function setSku(string $sku): OrderItemInterface
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $qty
     * @return OrderItemInterface
     */
    public function setQuantity(int $qty): OrderItemInterface
    {
        $this->quantity = $qty;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return OrderItemInterface
     */
    public function setPrice(float $price): OrderItemInterface
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    /**
     * @param string $permalink
     * @return $this
     */
    public function setPermalink(string $permalink): OrderItemInterface
    {
        $this->permalink = $permalink;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @param string $size
     * @return OrderItemInterface
     */
    public function setSize(string $size): OrderItemInterface
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFeedProductId(): ?int
    {
        return $this->feedProductId;
    }

    /**
     * @param int $id
     * @return OrderItemInterface
     */
    public function setFeedProductId(int $id): OrderItemInterface
    {
        $this->feedProductId = $id;
        return $this;
    }
}

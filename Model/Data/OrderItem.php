<?php
namespace Sizebay\SizebayTracker\Model\Data;

use Sizebay\SizebayTracker\Api\Data\OrderItemInterface;

/**
 * Class OrderItem
 * Implementa os dados de item de pedido para rastreamento Sizebay.
 */
class OrderItem implements OrderItemInterface
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
     * @return $this
     */
    public function setSku(string $sku): self
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
     * @return $this
     */
    public function setQuantity(int $qty): self
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
     * @return $this
     */
    public function setPrice(float $price): self
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
    public function setPermalink(string $permalink): self
    {
        $this->permalink = $permalink;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * @param string $size
     * @return $this
     */
    public function setSize(string $size): self
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
     * @return $this
     */
    public function setFeedProductId(int $id): self
    {
        $this->feedProductId = $id;
        return $this;
    }
}

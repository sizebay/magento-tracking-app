<?php
namespace Sizebay\SizebayTracker\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface OrderItemInterface extends ExtensibleDataInterface
{
    /**
     * Get product SKU
     *
     * @return string|null
     */
    public function getSku();

    /**
     * Set product SKU
     *
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * Get quantity
     *
     * @return int|null
     */
    public function getQuantity();

    /**
     * Set quantity
     *
     * @param int $qty
     * @return $this
     */
    public function setQuantity($qty);

    /**
     * Get price
     *
     * @return float|null
     */
    public function getPrice();

    /**
     * Set price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * Get permalink
     *
     * @return string|null
     */
    public function getPermalink();

    /**
     * Set permalink
     *
     * @param string $permalink
     * @return $this
     */
    public function setPermalink($permalink);

    /**
     * Get size
     *
     * @return string|null
     */
    public function getSize();

    /**
     * Set size
     *
     * @param string $size
     * @return $this
     */
    public function setSize($size);

    /**
     * Get feed product ID
     *
     * @return int|null
     */
    public function getFeedProductId();

    /**
     * Set feed product ID
     *
     * @param int $id
     * @return $this
     */
    public function setFeedProductId($id);
}

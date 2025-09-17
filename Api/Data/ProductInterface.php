<?php
namespace Sizebay\SizebayTracker\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ProductInterface extends ExtensibleDataInterface
{
    /**
     * Get Permalink
     *
     * @return string|null
     */
    public function getPermalink();

    /**
     * Set Permalink
     *
     * @param string $permalink
     * @return $this
     */
    public function setPermalink($permalink);

    /**
     * Get Permalink
     *
     * @return string|null
     */
    public function getProductId();

    /**
     * Set Permalink
     *
     * @param string $productId
     * @return $this
     */
    public function setProductId($productId);
}
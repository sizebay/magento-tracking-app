<?php

namespace Sizebay\SizebayTracker\Model\Data;


use Sizebay\SizebayTracker\Api\Data\ProductInterface;
use Magento\Framework\Model\AbstractExtensibleModel;


class Product extends AbstractExtensibleModel implements ProductInterface
{

    /**
     * @var string|null
     */
    private $permalink;

    /**
     * @var string|null
     */
    private $productId;

    /**
     * @return string|null
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param string $permalink
     * @return $this
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
    }

    public function getProductId()
    {
       return $this->productId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }
}
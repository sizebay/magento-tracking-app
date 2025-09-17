<?php
namespace Sizebay\SizebayTracker\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Sizebay\SizebayTracker\Api\Data\CartAddInterface;

class CartAdd extends AbstractExtensibleModel implements CartAddInterface
{
    /**
     * @var object[]
     */
    private $products = [];

    /**
     * @var string
     */
    private $sessionId = '';

    /**
     * @var string
     */
    private $tenantId = '';

    /**
     * @var string
     */
    private $referer = '';

    /**
     * @inheritdoc
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @inheritdoc
     */
    public function setProducts(array $products)
    {
        $this->products = $products;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @inheritdoc
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTenantId()
    {
        return $this->tenantId;
    }

    /**
     * @inheritdoc
     */
    public function setTenantId($tenantId)
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @inheritdoc
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
        return $this;
    }
}

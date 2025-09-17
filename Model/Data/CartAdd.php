<?php
namespace Sizebay\SizebayTracker\Model\Data;

use Sizebay\SizebayTracker\Api\Data\CartAddInterface;

class CartAdd extends AbstractExtensibleModel implements CartAddInterface
{
    /**
     * @var array
     */
    private $items = [];

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
     * @param array $items
     * @param string $sessionId
     * @param string $tenantId
     * @param string $referer
     */
    public function __construct($items = [], $sessionId = '', $tenantId = '', $referer = '')
    {
        $this->items = $items;
        $this->sessionId = $sessionId;
        $this->tenantId = $tenantId;
        $this->referer = $referer;
    }

    /**
     * @inheritdoc
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function setItems(array $items)
    {
        $this->items = $items;
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

<?php
namespace Sizebay\SizebayTracker\Model\Data;

use Sizebay\SizebayTracker\Api\Data\CartAddInterface;

class CartAdd implements CartAddInterface
{
    private $items = [];
    private $sessionId = '';
    private $tenantId = '';
    private $referer = '';

    public function __construct($items = [], $sessionId = '', $tenantId = '', $referer = '')
    {
        $this->items = $items;
        $this->sessionId = $sessionId;
        $this->tenantId = $tenantId;
        $this->referer = $referer;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    public function getTenantId()
    {
        return $this->tenantId;
    }

    public function setTenantId($tenantId)
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    public function getReferer()
    {
        return $this->referer;
    }

    public function setReferer($referer)
    {
        $this->referer = $referer;
        return $this;
    }
}

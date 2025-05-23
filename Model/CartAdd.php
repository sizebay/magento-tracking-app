<?php
namespace Sizebay\SizebayTracker\Model;

use Sizebay\SizebayTracker\Api\Data\CartAddInterface;

class CartAdd implements CartAddInterface
{
    private $items;
    private $sessionId;
    private $tenantId;
    private $referer;

    public function __construct($items, $sessionId, $tenantId, $referer)
    {
        $this->items = $items;
        $this->sessionId = $sessionId;
        $this->tenantId = $tenantId;
        $this->referer = $referer;
    }

    public function getItems() { return $this->items; }
    public function getSessionId() { return $this->sessionId; }
    public function getTenantId() { return $this->tenantId; }
    public function getReferer() { return $this->referer; }
}
?>
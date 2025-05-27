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

    /**
     * Get cart items
     *
     * @return string[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get session ID
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Get tenant ID
     *
     * @return string
     */
    public function getTenantId()
    {
        return $this->tenantId;
    }

    /**
     * Get referer URL
     *
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }
}
?>

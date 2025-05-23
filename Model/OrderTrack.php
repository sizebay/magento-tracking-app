<?php
namespace Sizebay\SizebayTracker\Model;

use Sizebay\SizebayTracker\Api\Data\OrderTrackInterface;

class OrderTrack implements OrderTrackInterface
{
    private $orderId;
    private $items;
    private $tenantId;
    private $referer;
    private $sessionId;
    private $currency;
    private $country;

    public function __construct(
        $orderId,
        array $items,
        $tenantId,
        $referer,
        $sessionId,
        $currency,
        $country
    ) {
        $this->orderId = $orderId;
        $this->items = $items;
        $this->tenantId = $tenantId;
        $this->referer = $referer;
        $this->sessionId = $sessionId;
        $this->currency = $currency;
        $this->country = $country;
    }

    /**
     * Get order ID
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Get order items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
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
     * Get currency code
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get country code
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}
?>

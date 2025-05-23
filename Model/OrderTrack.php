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

    public function getOrderId() { return $this->orderId; }
    public function getItems() { return $this->items; }
    public function getTenantId() { return $this->tenantId; }
    public function getReferer() { return $this->referer; }
    public function getSessionId() { return $this->sessionId; }
    public function getCurrency() { return $this->currency; }
    public function getCountry() { return $this->country; }
}
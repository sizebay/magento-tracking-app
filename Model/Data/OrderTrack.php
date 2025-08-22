<?php
namespace Sizebay\SizebayTracker\Model\Data;

use Sizebay\SizebayTracker\Api\Data\OrderTrackInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class OrderTrack implements OrderTrackInterface
{
    /**
     * @var string
     */
    private $orderId;

    /**
     * @var object[]
     */
    private $items = [];

    /**
     * @var string
     */
    private $tenantId;

    /**
     * @var string
     */
    private $referer;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $country;

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
     * Set order ID
     *
     * @param string $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * Get order items
     *
     * @return string[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set order items
     *
     * @param string[] $items
     * @return $this
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
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
     * Set tenant ID
     *
     * @param string $tenantId
     * @return $this
     */
    public function setTenantId($tenantId)
    {
        $this->tenantId = $tenantId;
        return $this;
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
     * Set referer URL
     *
     * @param string $referer
     * @return $this
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
        return $this;
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
     * Set session ID
     *
     * @param string $sessionId
     * @return $this
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
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
     * Set currency code
     *
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
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

    /**
     * Set country code
     *
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
}

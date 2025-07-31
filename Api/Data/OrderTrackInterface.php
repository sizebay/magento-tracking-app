<?php
namespace Sizebay\SizebayTracker\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface OrderTrackInterface extends ExtensibleDataInterface
{
    /**
     * Get order ID
     *
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order ID
     *
     * @param string $orderId
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * Get items
     *
     * @return object[]|null
     */
    public function getItems();

    /**
     * Set items
     *
     * @param object $items
     * @return $this
     */
    public function setItems(array $items);

    /**
     * Get tenant ID
     *
     * @return string|null
     */
    public function getTenantId();

    /**
     * Set tenant ID
     *
     * @param string $tenantId
     * @return $this
     */
    public function setTenantId($tenantId);

    /**
     * Get referer
     *
     * @return string|null
     */
    public function getReferer();

    /**
     * Set referer
     *
     * @param string $referer
     * @return $this
     */
    public function setReferer($referer);

    /**
     * Get session ID
     *
     * @return string|null
     */
    public function getSessionId();

    /**
     * Set session ID
     *
     * @param string $sessionId
     * @return $this
     */
    public function setSessionId($sessionId);

    /**
     * Get currency
     *
     * @return string|null
     */
    public function getCurrency();

    /**
     * Set currency
     *
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency);

    /**
     * Get country
     *
     * @return string|null
     */
    public function getCountry();

    /**
     * Set country
     *
     * @param string $country
     * @return $this
     */
    public function setCountry($country);
}

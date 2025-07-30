<?php
namespace Sizebay\SizebayTracker\Api\Data;

/**
 * Interface OrderTrackInterface
 * Represents the structure of an order tracking message.
 */
interface OrderTrackInterface
{
    /**
     * Get the unique order ID.
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Get the items included in the order.
     *
     * @return array
     */
    public function getItems();

    /**
     * Get the tenant ID related to the order.
     *
     * @return string
     */
    public function getTenantId();

    /**
     * Get the referer information.
     *
     * @return string
     */
    public function getReferer();

    /**
     * Get the session ID at the time of the order.
     *
     * @return string
     */
    public function getSessionId();

    /**
     * Get the currency used for the order.
     *
     * @return string
     */
    public function getCurrency();

    /**
     * Get the country from where the order was made.
     *
     * @return string
     */
    public function getCountry();
}

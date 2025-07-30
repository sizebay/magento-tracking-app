<?php
namespace Sizebay\SizebayTracker\Api\Data;

/**
 * Interface CartAddInterface
 * Represents the structure of a cart addition tracking message.
 */
interface CartAddInterface
{
    /**
     * Get the items added to the cart.
     *
     * @return array
     */
    public function getItems();

    /**
     * Get the session ID at the time items were added.
     *
     * @return string
     */
    public function getSessionId();

    /**
     * Get the tenant ID related to the session.
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
}

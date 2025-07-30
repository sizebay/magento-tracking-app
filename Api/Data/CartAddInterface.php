<?php
namespace Sizebay\SizebayTracker\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface CartAddInterface extends ExtensibleDataInterface
{
    /**
     * Get items
     *
     * @return array|null
     */
    public function getItems();

    /**
     * Set items
     *
     * @param array $items
     * @return $this
     */
    public function setItems(array $items);

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
}

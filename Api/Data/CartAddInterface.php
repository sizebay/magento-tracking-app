<?php
namespace Sizebay\SizebayTracker\Api\Data;

interface CartAddInterface
{
    public function getItems();
    public function setItems(array $items);

    public function getSessionId();
    public function setSessionId($sessionId);

    public function getTenantId();
    public function setTenantId($tenantId);

    public function getReferer();
    public function setReferer($referer);
}

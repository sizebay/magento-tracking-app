<?php
namespace Sizebay\SizebayTracker\Api\Data;

interface OrderTrackInterface
{
    public function getOrderId();
    public function setOrderId($orderId);

    public function getItems();
    public function setItems(array $items);

    public function getTenantId();
    public function setTenantId($tenantId);

    public function getReferer();
    public function setReferer($referer);

    public function getSessionId();
    public function setSessionId($sessionId);

    public function getCurrency();
    public function setCurrency($currency);

    public function getCountry();
    public function setCountry($country);
}

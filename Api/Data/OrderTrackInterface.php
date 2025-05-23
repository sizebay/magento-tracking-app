<?php
namespace Sizebay\SizebayTracker\Api\Data;

interface OrderTrackInterface
{
    public function getOrderId();
    public function getItems();
    public function getTenantId();
    public function getReferer();
    public function getSessionId();
    public function getCurrency();
    public function getCountry();
}

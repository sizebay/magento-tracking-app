<?php
namespace Sizebay\SizebayTracker\Api\Data;

interface CartAddInterface {
    public function getItems(); // define needed getters
    public function getSessionId();
    public function getTenantId();
    public function getReferer();
}
<?php
namespace Sizebay\SizebayTracker\Model\Consumer;

use Psr\Log\LoggerInterface;
use Sizebay\SizebayTracker\Api\Data\CartAddInterface;

class CartAddConsumer
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(CartAddInterface $cartAdd)
    {
        try {
            $url = "https://vfr-v3-production.sizebay.technology/plugin/new/cart?sid=" . $cartAdd->getSessionId();

            $data = [
                "products" => $cartAdd->getItems(),
                "tenantId" => $cartAdd->getTenantId()
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'content-type: application/json',
                'accept: application/json',
                'device: DESKTOP',
                'tenant_id: ' . $cartAdd->getTenantId(),
                'referer: ' . $cartAdd->getReferer(),
            ]);

            $response = curl_exec($ch);
            if ($response === false) {
                $this->logger->error('Cart tracking failed: ' . curl_error($ch));
            }

            curl_close($ch);
        } catch (\Exception $e) {
            $this->logger->error('Sizebay Cart Queue Error: ' . $e->getMessage());
        }
    }
}

?>

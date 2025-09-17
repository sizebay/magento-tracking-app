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
        $this->logger->info("Sizebay Cart Consumer Used:");
        try {
            $url = "https://vfr-v3-production.sizebay.technology/plugin/new/cart?sid=" . $cartAdd->getSessionId();

            $this->logger->info($url);

            $items = array_map(function ($product) {
                return [
                    'product_id' => $product->getProductId(),
                    'permalink'  => $product->getPermalink(),
                ];
            }, $cartAdd->getProducts());
            
            $data = [
                "products" => $items,
                "tenantId" => $cartAdd->getTenantId()
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'content-type: application/json',
                'accept: application/json',
                'x-szb-device: DESKTOP',
                'x-szb-tenant-id: ' . $cartAdd->getTenantId(),
                'x-szb-referer: ' . $cartAdd->getReferer(),
            ]);

            $response = curl_exec($ch);
            if ($response === false) {
                $this->logger->error('Cart tracking failed: ' . curl_error($ch));
            }

            $this->logger->info($response);
            curl_close($ch);
        } catch (\Exception $e) {
            $this->logger->error('Sizebay Cart Queue Error: ' . $e->getMessage());
        }
    }
}

?>

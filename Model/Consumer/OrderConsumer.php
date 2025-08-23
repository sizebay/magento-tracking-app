<?php
namespace Sizebay\SizebayTracker\Model\Consumer;

use Psr\Log\LoggerInterface;
use Sizebay\SizebayTracker\Api\Data\OrderTrackInterface;
use Sizebay\SizebayTracker\Api\Data\OrderItemInterface;

class OrderConsumer
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(OrderTrackInterface $data): void
    {
        $this->logger->info("Sizebay Order Consumer Used:");
        try {
            $url = 'https://vfr-v3-production.sizebay.technology/plugin/new/ordered?sid='
                . urlencode((string)$data->getSessionId());

            $this->logger->info('Request URL: ' . $url);

            // Convert items from OrderItemInterface objects → array
            $items = [];
            foreach ((array)$data->getItems() as $item) {
                if ($item instanceof OrderItemInterface) {
                    $items[] = [
                        'sku'           => $item->getSku(),
                        'quantity'      => $item->getQuantity(),
                        'price'         => $item->getPrice(),
                        'permalink'     => $item->getPermalink(),
                        'size'          => $item->getSize(),
                        'feedProductId' => $item->getFeedProductId(),
                    ];
                } elseif (is_array($item)) {
                    $items[] = $item;
                } elseif (is_string($item)) {
                    $decoded = json_decode($item, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $items[] = $decoded;
                    }
                }
            }

            // Build payload
            $payload = [
                'orderId'  => $data->getOrderId(),
                'items'    => $items,
                'tenantId' => $data->getTenantId(),
                'currency' => $data->getCurrency(),
                'country'  => $data->getCountry(),
            ];

            $this->logger->info('Outgoing Payload: ' . json_encode($payload));

            // Send via cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'content-type: application/json',
                'accept: application/json',
                'device: DESKTOP',
                'tenant_id: ' . $data->getTenantId(),
                'referer: ' . (string)$data->getReferer(),
            ]);

            $response = curl_exec($ch);
            if ($response === false) {
                $err = curl_error($ch);
                curl_close($ch);
                throw new \RuntimeException('cURL error: ' . $err);
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $this->logger->info('HTTP ' . $httpCode . ' Response: ' . $response);
        } catch (\Throwable $e) {
            $this->logger->error('Error in OrderConsumer: ' . $e->getMessage());
        }
    }
}

<?php
namespace Sizebay\SizebayTracker\Model\Consumer;

use Psr\Log\LoggerInterface;
use Sizebay\SizebayTracker\Api\Data\OrderTrackInterface;

class OrderConsumer
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(OrderTrackInterface $data)
    {
        $this->logger->info("Sizebay Order Consumer Used");

        try {
            $url = "https://vfr-v3-production.sizebay.technology/plugin/new/ordered?sid=" . $data->getSessionId();
            $this->logger->info("Request URL: " . $url);

            $payload = [
                "orderId"  => $data->getOrderId(),
                "items"    => $data->getItems(), // Assuming it's already an array of arrays
                "tenantId" => $data->getTenantId(),
                "currency" => $data->getCurrency(),
                "country"  => $data->getCountry()
            ];

            $this->logger->info('Outgoing Payload: ' . json_encode($payload));

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode($payload),
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Device: DESKTOP',
                    'tenant_id: ' . $data->getTenantId(),
                    'referer: ' . $data->getReferer(),
                ],
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT        => 10,
            ]);

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($response === false || $httpcode >= 400) {
                throw new \Exception("HTTP {$httpcode} error: {$curlError}");
            }

            $this->logger->info("Response: " . $response);
        } catch (\Exception $e) {
            $this->logger->error('Error in OrderConsumer: ' . $e->getMessage());
        }
    }
}
?>
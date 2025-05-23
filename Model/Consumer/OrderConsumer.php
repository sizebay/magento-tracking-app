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
            $this->logger->info("Consumer Used:");
            try {
                $url = "https://vfr-v3-production.sizebay.technology/plugin/new/ordered?sid=" . $data['session_id'];
    
                $ch = curl_init($url);
    
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    "orderId" => $data['order_id'],
                    "items" => $data['items'],
                    "tenantId" => $data['tenant_id'],
                    "currency" => $data['currency'],
                    "country" => $data['country']
                ]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'content-type: application/json',
                    'accept: application/json',
                    'device: DESKTOP',
                    'tenant_id: ' . $data['tenant_id'],
                    'referer: ' . $data['referer'],
                ]);
    
                $response = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($response === false) {
                    throw new \Exception("cURL error: " . curl_error($ch));
                }
                curl_close($ch);
                $this->logger->info($response);
            } catch (\Exception $e) {
                $this->logger->error('Error in OrderPlacedConsumer: ' . $e->getMessage());
            }
        }
    }
?>
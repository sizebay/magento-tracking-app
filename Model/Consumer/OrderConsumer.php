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
                $url = "https://vfr-v3-production.sizebay.technology/plugin/new/ordered?sid=" . $data->getSessionId();
    
                $this->logger->info($url);

                $ch = curl_init($url);
    
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,[
                    "orderId" => $data->getOrderId(),
                    "items" => $data->getItems(),
                    "tenantId" => $data->getTenantId(),
                    "currency" => $data->getCurrency(),
                    "country" => $data->getCountry()
                ]);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'content-type: application/json',
                    'accept: application/json',
                    'device: DESKTOP',
                    'tenant_id: ' . $data->getTenantId(),
                    'referer: ' . $data->getReferer(),
                ]);

                $this->logger->info('Outgoing Payload: ' . json_encode([
                    "orderId" => $data->getOrderId(),
                    "items" => $data->getItems(),
                    "tenantId" => $data->getTenantId(),
                    "currency" => $data->getCurrency(),
                    "country" => $data->getCountry()
                ]));
                
    
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
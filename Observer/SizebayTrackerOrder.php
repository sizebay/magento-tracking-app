<?php

namespace Sizebay\SizebayTracker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class SizebayTrackerOrder implements ObserverInterface
{
    protected $logger;
    protected $scopeConfig;

    public function __construct(LoggerInterface $logger, ScopeConfigInterface $scopeConfig)
    {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    private function executeOrderPluginRequest($order)
    {
        try {
            $tenant_id = strval($this->scopeConfig->getValue('sizebay_sizebaytracker/settings/tenant_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
            $referer = strval($this->scopeConfig->getValue('sizebay_sizebaytracker/settings/referer', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
            $country = strval($this->scopeConfig->getValue('sizebay_sizebaytracker/settings/country', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));

            $sessionId = $_COOKIE["SIZEBAY_SESSION_ID_V4"];
            $url = "https://vfr-v3-production.sizebay.technology/plugin/new/ordered?sid=" . $sessionId;

            $items = [];
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

            foreach ($order->getAllVisibleItems() as $item) {
                $product = $item->getProduct();
                $productOptions = $item->getProductOptions();
                $size = '';

                $sizeAttributeId = $this->scopeConfig->getValue(
                    'sizebay_sizebaytracker/settings/size_attribute',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );

                // If the size attribute is configured
                if (!empty($sizeAttributeId)) {
                    if (isset($productOptions['attributes_info'])) {
                        foreach ($productOptions['attributes_info'] as $attributeInfo) {
                            // Check if the attribute matches the configured size attribute
                            if ($attributeInfo['option_id'] == $sizeAttributeId) {
                                $size = $attributeInfo['value'];
                                break;
                            }
                        }
                    }
                }

                isset($size) ? $size : 'N/A';

                $items[] = [
                    "permalink" => $storeManager->getStore()->getBaseUrl() . $product->getId(),
                    "price" => $item->getPrice(),
                    "quantity" => intval($item->getQtyOrdered()),
                    "size" => $size,
                    "feedProductId" => $product->getId(),
                    "sku" => $item->getSku()
                ];
            }


            $data = [
                "orderId" => $order->getId(),
                "items" => $items,
                "tenantId" => $tenant_id,
                "currency" => $order->getOrderCurrencyCode(),
                "country" => $country,
            ];

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'content-type: application/json',
                'accept: application/json',
                'device: DESKTOP',
                'tenant_id: ' . $tenant_id,
                'referer: ' . $referer,
            ]);

            $response = curl_exec($ch);

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $error = curl_error($ch);
            if ($response === false) {
                $errno = curl_errno($ch);
                curl_close($ch);
                throw new \Exception(sprintf('Response code: %s. Error: %s', (string) $httpcode, $error), $errno);
            }

            $response = json_decode($response);

            curl_close($ch);
        } catch (\Exception $e) {
            $this->logger->error('Error in SizebayTracker fetch: ' . $e->getMessage());
        }
    }

    public function isModuleActive()
    {
        return $this->scopeConfig->isSetFlag(
            'sizebay_sizebaytracker/settings/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        try {
            if ($this->isModuleActive()) {
                $this->executeOrderPluginRequest($order);
            }
        } catch (\Exception $e) {
            $this->logger->error('Error in SizebayTracker observer: ' . $e->getMessage());
        }
    }

}

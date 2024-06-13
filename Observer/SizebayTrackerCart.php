<?php

namespace SizeBay\SizeBayTracker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class SizebayTrackerCart implements ObserverInterface
{
    protected $logger;
    protected $scopeConfig;
    protected $checkoutSession;

    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
    }

    private function executeAddToCartPluginRequest($addedItems)
    {
        try {
            $tenant_id = strval($this->scopeConfig->getValue('sizebay_sizebaytracker/settings/tenant_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
            $referer = strval($this->scopeConfig->getValue('sizebay_sizebaytracker/settings/referer', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));

            $sessionId = $_COOKIE["SIZEBAY_SESSION_ID_V4"];
            $url = "https://vfr-v3-production.sizebay.technology/plugin/new/cart?sid=" . $sessionId;

            $items = [];

            foreach ($addedItems as $item) {
                $items[] = [
                    "permalink" => $item['permalink'],
                ];
            }

            $data = [
                "products" => $items,
                "tenantId" => $tenant_id,
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
            $httpcode = curl_getinfo($ch);
            $error = curl_error($ch);

            if ($response === false) {
                $errno = curl_errno($ch);
                curl_close($ch);
                throw new \Exception(sprintf('Response code : %s. Error : %s', (string) $httpcode['http_code'], $error), $errno);
            }

            curl_close($ch);
        } catch (\Exception $e) {
            $this->logger->error('Error in SizeBayTracker fetch: ' . $e->getMessage());
        }
    }

    public function execute(Observer $observer)
    {
        $cart = $observer->getEvent()->getCart();
        try {
            $quote = $this->checkoutSession->getQuote();
            $items = $quote->getAllVisibleItems();
            $previousItems = $this->checkoutSession->getSizebayAddedItems() ?: [];

            // Find newly added items
            $addedItems = [];
            foreach ($items as $item) {
                $productId = $item->getProduct()->getId();
                if (!in_array($productId, $previousItems)) {
                    $addedItems[] = [
                        "permalink" => $item->getProduct()->getProductUrl(), // Adjust permalink retrieval
                    ];
                }
            }

            // Update session with current added items
            $this->checkoutSession->setSizebayAddedItems(array_merge($previousItems, array_column($addedItems, 'product_id')));

            // Execute request only if there are newly added items
            if (!empty($addedItems)) {
                $this->executeAddToCartPluginRequest($addedItems);
            }
        } catch (\Exception $e) {
            $this->logger->error('Error in SizeBayTracker observer: ' . $e->getMessage());
        }
    }
}

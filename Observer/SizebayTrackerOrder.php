<?php

namespace Sizebay\SizebayTracker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Sizebay\SizebayTracker\Model\Publisher\OrderPublisher;
use Magento\Store\Model\StoreManagerInterface;

class SizebayTrackerOrder implements ObserverInterface
{
    protected $logger;
    protected $scopeConfig;
    protected $orderPlacedPublisher;
    protected $storeManager;

    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        OrderPublisher $orderPublisher,
        StoreManagerInterface $storeManager
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->orderPublisher = $orderPublisher;
        $this->storeManager = $storeManager;
    }

    public function isModuleActive(): bool
    {
        return $this->scopeConfig->isSetFlag(
            'sizebay_sizebaytracker/settings/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function execute(Observer $observer)
    {
        try {
            if (!$this->isModuleActive()) {
                return;
            }

            $order = $observer->getEvent()->getOrder();

            $tenantId = $this->scopeConfig->getValue('sizebay_sizebaytracker/settings/tenant_id');
            $referer = $this->scopeConfig->getValue('sizebay_sizebaytracker/settings/referer');
            $country = $this->scopeConfig->getValue('sizebay_sizebaytracker/settings/country');
            $sizeAttributeId = $this->scopeConfig->getValue('sizebay_sizebaytracker/settings/size_attribute');
            $sessionId = $_COOKIE['SIZEBAY_SESSION_ID_V4'] ?? '';

            $items = [];

            foreach ($order->getAllVisibleItems() as $item) {
                $product = $item->getProduct();
                $productOptions = $item->getProductOptions();

                $size = 'N/A';
                if (!empty($sizeAttributeId) && isset($productOptions['attributes_info'])) {
                    foreach ($productOptions['attributes_info'] as $attr) {
                        if ($attr['option_id'] == $sizeAttributeId) {
                            $size = $attr['value'];
                            break;
                        }
                    }
                }

                $items[] = [
                    'permalink' => $this->storeManager->getStore()->getBaseUrl() . $product->getId(),
                    'price' => $item->getPrice(),
                    'quantity' => (int) $item->getQtyOrdered(),
                    'size' => $size,
                    'feedProductId' => $product->getId(),
                    'sku' => $item->getSku()
                ];
            }

            $this->orderPublisher->publish([
                'order_id' => $order->getId(),
                'items' => $items,
                'tenant_id' => $tenantId,
                'referer' => $referer,
                'session_id' => $sessionId,
                'currency' => $order->getOrderCurrencyCode(),
                'country' => $country,
            ]);

        } catch (\Exception $e) {
            $this->logger->error('Error in SizebayTrackerOrder observer: ' . $e->getMessage());
        }
    }
}


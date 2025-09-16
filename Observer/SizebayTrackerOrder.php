<?php

namespace Sizebay\SizebayTracker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use sizebay\SizebayTracker\Model\Publisher\OrderPublisher;
use Magento\Store\Model\StoreManagerInterface;

class SizebayTrackerOrder implements ObserverInterface
{
    protected $logger;
    protected $scopeConfig;
    protected $orderPublisher;
    protected $storeManager;

    protected $orderItemFactory;

    protected $orderTrackFactory;


    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        OrderPublisher $orderPublisher,
        StoreManagerInterface $storeManager,
        \Sizebay\SizebayTracker\Model\Data\OrderItemFactory $orderItemFactory,
        \Sizebay\SizebayTracker\Model\Data\OrderTrackFactory $orderTrackFactory
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->orderPublisher = $orderPublisher;
        $this->storeManager = $storeManager;
        $this->orderItemFactory = $orderItemFactory;
        $this->orderTrackFactory = $orderTrackFactory;
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

            try {
                $orderItem = $this->orderItemFactory->create();
                $orderItem->setSku($item->getSku());
                $orderItem->setQuantity((int)$item->getQtyOrdered());
                $orderItem->setPrice((float)$item->getPrice());$permalink = $this->storeManager->getStore()->getBaseUrl() . $product->getId();
                $orderItem->setPermalink($permalink);
                $orderItem->setSize($size);
                $orderItem->setFeedProductId($product->getId());

                $items[] = $orderItem;
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }

        try {
            $orderTrack = $this->orderTrackFactory->create();
            $orderTrack->setOrderId($order->getId())
                ->setItems($items)
                ->setTenantId($tenantId)
                ->setReferer($referer)
                ->setSessionId($sessionId)
                ->setCurrency($order->getOrderCurrencyCode())
                ->setCountry($country);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

        try {
        $this->orderPublisher->publish($orderTrack);
        $this->logger->info("Sizebay Order is published 2: " . json_encode($orderTrack));
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}


<?php

namespace Sizebay\SizebayTracker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Sizebay\SizebayTracker\Model\Publisher\CartAddPublisher;
use Magento\Store\Model\ScopeInterface;

class SizebayTrackerCart implements ObserverInterface
{
    protected $logger;
    protected $scopeConfig;
    protected $checkoutSession;
    protected $cartAddPublisher;
    protected $cartAddFactory;
    protected $productFactory;

    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession,
        CartAddPublisher $cartAddPublisher,
        \Sizebay\SizebayTracker\Model\Data\CartAddFactory $cartAddFactory,
        \Sizebay\SizebayTracker\Model\Data\ProductFactory $productFactory
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->cartAddPublisher = $cartAddPublisher;
        $this->cartAddFactory = $cartAddFactory;
        $this->productFactory = $productFactory;
    }

    public function isModuleActive(): bool
    {
        return $this->scopeConfig->isSetFlag(
            'sizebay_sizebaytracker/settings/active',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function execute(Observer $observer)
    {
        try {
            $product = $observer->getEvent()->getProduct();

            if (!$product || !$product->getId()) {
                $this->logger->warning("SizebayTrackerCartAdd: No product found in event.");
                return;
            }

            $this->logger->info("SizebayTrackerCart: Current items in cart: " . count($product));

            $addedItems = [];
            foreach ($product as $p) {
                $productId = $p->getId();
                    try {
                        $product = $this->productFactory->create();
                        $product->setPermalink($p->getProductUrl());
                        $product->setProductId($productId);

                        $addedItems[] = $product;

                        $this->logger->info("SizebayTrackerCart: New product added [ID: $productId, URL: " . $p->getProductUrl() . "]");
                    } catch (\Exception $e) {
                        $this->logger->error("SizebayTrackerCart: Error creating product data for ID $productId - " . $e->getMessage());
                    }
            }

            if (!empty($addedItems)) {
                $tenantId = $this->scopeConfig->getValue('sizebay_sizebaytracker/settings/tenant_id', ScopeInterface::SCOPE_STORE);
                $referer = $this->scopeConfig->getValue('sizebay_sizebaytracker/settings/referer', ScopeInterface::SCOPE_STORE);
                $sessionId = $_COOKIE['SIZEBAY_SESSION_ID_V4'] ?? '';

                $this->logger->info("SizebayTrackerCart: Preparing to publish event. TenantID: $tenantId, SessionID: $sessionId, Referer: $referer");

                try {
                    $cartAdd = $this->cartAddFactory->create();
                    $cartAdd->setSessionId($sessionId);
                    $cartAdd->setReferer($referer);
                    $cartAdd->setTenantId($tenantId);
                    $cartAdd->setProducts($addedItems);

                    $this->cartAddPublisher->publish($cartAdd);

                    $this->logger->info("SizebayTrackerCart: Event published successfully: " . json_encode([
                            'sessionId' => $sessionId,
                            'tenantId'  => $tenantId,
                            'referer'   => $referer,
                            'products'     => array_map(function ($p) {
                                return $p->getProductId();
                            }, $addedItems)
                        ]));
                } catch (\Exception $e) {
                    $this->logger->error("SizebayTrackerCart: Failed to publish event - " . $e->getMessage());
                }
            } else {
                $this->logger->info("SizebayTrackerCart: No new items to publish.");
            }
        } catch (\Exception $e) {
            $this->logger->error('SizebayTrackerCart: Fatal error - ' . $e->getMessage());
        }
    }
}

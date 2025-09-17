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
            $quoteItem = $observer->getEvent();

            $this->logger->info($quoteItem);
            if (!$quoteItem) {
                $this->logger->warning("SizebayTrackerCartAdd: No quote item found in event.");
                return;
            }

            $product = $quoteItem->getProduct();
            if (!$product || !$product->getId()) {
                $this->logger->warning("SizebayTrackerCartAdd: No product found in quote item.");
                return;
            }

            $productId = $product->getId();
            $permalink = $product->getProductUrl();
            $quantity = $quoteItem->getQty();

            $this->logger->info("SizebayTrackerCart: Product added [ID: $productId, Qty: $quantity, URL: $permalink]");

            $productData = $this->productFactory->create();
            $productData->setProductId($productId);
            $productData->setPermalink($permalink);

            $cartAdd = $this->cartAddFactory->create();
            $cartAdd->setSessionId($_COOKIE['SIZEBAY_SESSION_ID_V4'] ?? '');
            $cartAdd->setReferer($this->scopeConfig->getValue('sizebay_sizebaytracker/settings/referer', ScopeInterface::SCOPE_STORE));
            $cartAdd->setTenantId($this->scopeConfig->getValue('sizebay_sizebaytracker/settings/tenant_id', ScopeInterface::SCOPE_STORE));
            $cartAdd->setProducts([$productData]);

            $this->cartAddPublisher->publish($cartAdd);

            $this->logger->info("SizebayTrackerCart: Event published successfully for product ID $productId");

        } catch (\Exception $e) {
            $this->logger->error('SizebayTrackerCart: Fatal error - ' . $e->getMessage());
        }
    }
}

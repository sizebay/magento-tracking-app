<?php

namespace Sizebay\SizebayTracker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Sizebay\SizebayTracker\Model\Publisher\CartAddPublisher;
use Magento\Store\Model\StoreManagerInterface;
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
        try {
            $quote = $this->checkoutSession->getQuote();
            $items = $quote->getAllVisibleItems();
            $previousItems = $this->checkoutSession->getSizebayAddedItems() ?: [];

            $addedItems = [];
            foreach ($items as $item) {
                $productId = $item->getProduct()->getId();
                if (!in_array($productId, $previousItems)) {
                    $product = $this->productFactory->create();
                    $product->setPermalink($item->getProduct()->getProductUrl());
                    $product->setProductId($productId);
                    $addedItems->push($product);
                }
            }

            $this->checkoutSession->setSizebayAddedItems(array_merge(
                $previousItems,
                array_column($addedItems, 'product_id')
            ));

            if (!empty($addedItems) && $this->isModuleActive()) {
                $tenantId = $this->scopeConfig->getValue('sizebay_sizebaytracker/settings/tenant_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                $referer = $this->scopeConfig->getValue('sizebay_sizebaytracker/settings/referer', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

                $sessionId = $_COOKIE['SIZEBAY_SESSION_ID_V4'] ?? '';

                $this->logger->info('SizebayTrackerCart fired');

                $cartAdd = $this->cartAddFactory->create();
                $cartAdd->setSessionId($sessionId);
                $cartAdd->setReferer($referer);
                $cartAdd->setTantnId($tenantId);
                $cartAdd->setItems($addedItems);
                $this->cartAddPublisher->publish($cartAdd);
            }
        } catch (\Exception $e) {
            $this->logger->error('Error in SizebayTrackerCart observer: ' . $e->getMessage());
        }
    }
}

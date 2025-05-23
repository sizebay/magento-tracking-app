<?php

namespace Sizebay\SizebayTracker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Sizebay\SizebayTracker\Model\Publisher\CartAddPublisher;
use Sizebay\SizebayTracker\Model\CartAdd;

class SizebayTrackerCart implements ObserverInterface
{
    protected $logger;
    protected $scopeConfig;
    protected $checkoutSession;
    protected $cartAddPublisher;

    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession,
        CartAddPublisher $cartAddPublisher
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->cartAddPublisher = $cartAddPublisher;
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
                    $addedItems[] = [
                        "product_id" => $productId,
                        "permalink" => $item->getProduct()->getProductUrl(),
                    ];
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

                $cartAdd = new CartAdd($addedItems, $sessionId, $tenantId, $referer);
                $this->cartAddPublisher->publish($cartAdd);
            }
        } catch (\Exception $e) {
            $this->logger->error('Error in SizebayTrackerCart observer: ' . $e->getMessage());
        }
    }
}

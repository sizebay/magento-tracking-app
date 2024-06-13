<?php

namespace SizeBay\SizeBayTracker\Controller\Adminhtml\Settings;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SizeBay_SizeBayTracker::sizebaytracker_settings');
        $resultPage->getConfig()->getTitle()->prepend(__('SizeBay Tracker Settings'));

        return $resultPage;
    }
}

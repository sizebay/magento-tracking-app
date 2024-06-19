<?php
<?php
namespace Sizebay\SizebayTracker\Model\Config\Source;

use Magento\Framework\App\Config\Value;
use Magento\Store\Model\StoreManagerInterface;

class Referer extends Value
{
    protected $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            null, // Assuming the resource model is not needed, pass null.
            $resourceCollection,
            $data
        );
    }

    public function afterLoad()
    {
        $referer = $this->getValue();
        if ($referer === null || trim($referer) === '') {
            $baseUrl = $this->storeManager->getStore()->getBaseUrl();
            $domain = parse_url($baseUrl, PHP_URL_HOST);
            $this->setValue($domain);
        }
        return parent::afterLoad();
    }
}

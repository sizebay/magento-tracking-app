<?php
namespace SizeBay\SizeBayTracker\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as AttributeCollectionFactory;

class ProductAttributes implements OptionSourceInterface
{
    protected $attributeCollectionFactory;

    public function __construct(AttributeCollectionFactory $attributeCollectionFactory)
    {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    public function toOptionArray()
    {
        $options = [];

        // Load all product attributes
        $attributeCollection = $this->attributeCollectionFactory->create();

        foreach ($attributeCollection as $attribute) {
            $options[] = [
                'value' => $attribute->getId(),
                'label' => $attribute->getFrontendLabel()
            ];
        }

        return $options;
    }
}

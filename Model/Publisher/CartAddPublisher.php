<?php

namespace Sizebay\SizebayTracker\Model\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;
use Sizebay\SizebayTracker\Api\Data\CartAddInterface;

class CartAddPublisher
{
    const TOPIC_NAME = 'sizebay.cart.add';

    protected $publisher;

    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    public function publish(CartAddInterface $cartAdd)
    {
        $this->publisher->publish(self::TOPIC_NAME, $cartAdd);
    }
}
}

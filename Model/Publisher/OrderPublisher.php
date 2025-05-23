<?php
namespace Sizebay\SizebayTracker\Model\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;
use Sizebay\SizebayTracker\Api\Data\OrderTrackInterface;

class OrderPublisher
{
    const TOPIC_NAME = 'sizebay.order';

    private $publisher;

    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    public function publish(array $data)
    {
        $this->publisher->publish(self::TOPIC_NAME, $data);
    }
}
?>
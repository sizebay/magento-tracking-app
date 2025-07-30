<?php
namespace Sizebay\SizebayTracker\Model\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;
use SIzebay\SizebayTracker\Api\Data\OrderTrackInterface;

class OrderPublisher
{
    const TOPIC_NAME = 'sizebay.order';

    protected $publisher;

    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    public function publish(OrderTrackInterface $orderTrack)
    {
        try {
        $this->publisher->publish(self::TOPIC_NAME, $orderTrack);
        } catch (Exception $exception) {
            $this->logger->error('Error in OrderPublisher: Topic - ' . self::TOPIC_NAME . " EXCEPTION - " . $exception->getMessage() . " ORDER - "  . $orderTrack);
        }
    }
}
?>
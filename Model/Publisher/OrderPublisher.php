<?php
namespace Sizebay\SizebayTracker\Model\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;
use Sizebay\SizebayTracker\Api\Data\OrderTrackInterface;
use Psr\Log\LoggerInterface;
use Exception;

class OrderPublisher
{
    const TOPIC_NAME = 'sizebay.order';

    protected $publisher;
    protected $logger;

    public function __construct(
        PublisherInterface $publisher,
        LoggerInterface $logger
    ) {
        $this->publisher = $publisher;
        $this->logger = $logger;
    }

    public function publish(OrderTrackInterface $orderTrack)
    {
        try {
            // Consider publishing getData() array instead if serialization is an issue
            $this->publisher->publish(self::TOPIC_NAME, $orderTrack);
        } catch (Exception $exception) {
            $this->logger->error(
                'Error in OrderPublisher: Topic - ' . self::TOPIC_NAME .
                ' EXCEPTION - ' . $exception->getMessage() .
                ' ORDER - ' . print_r($orderTrack->getData(), true)
            );
        }
    }
}

?>
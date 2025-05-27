<?php

namespace Sizebay\SizebayTracker\Model\Cron;

class RunOrderConsumer
{
    public function execute()
    {
        $consumer = 'sizebay.order';
        $cmd = 'php ' . BP . '/bin/magento queue:consumers:start ' . $consumer . ' --max-messages=100 --single-thread';
        exec($cmd . ' > /dev/null 2>&1 &');
        return $this;
    }
}

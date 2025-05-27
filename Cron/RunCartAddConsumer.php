<?php

namespace Sizebay\SizebayTracker\Model\Cron;

class RunCartAddConsumer
{
    public function execute()
    {
        $consumer = 'sizebay.cart.add'; 
        $cmd = 'php ' . BP . '/bin/magento queue:consumers:start ' . $consumer . ' --max-messages=100 --single-thread';
        exec($cmd . ' > /dev/null 2>&1 &');
        return $this;
    }
}

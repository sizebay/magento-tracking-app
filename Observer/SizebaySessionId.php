<?php

namespace Sizebay\SizebayTracker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class SizebaySessionId implements ObserverInterface
{
    protected $logger;
    protected $scopeConfig;

    public function __construct(LoggerInterface $logger, ScopeConfigInterface $scopeConfig,)
    {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    private function requestSessionID($retryCount = 3)
    {
        $session_url = "https://vfr-v3-production.sizebay.technology/api/me/session-id";

        for ($i = 0; $i < $retryCount; $i++) {
            $session_request = curl_init($session_url);
            curl_setopt($session_request, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($session_request);
            $httpcode = curl_getinfo($session_request, CURLINFO_HTTP_CODE);
            $error = curl_error($session_request);

            if ($response === false) {
                $errno = curl_errno($session_request);
                curl_close($session_request);
                throw new \Exception(sprintf('Response code: %s. Error: %s', $httpcode, $error), $errno);
            }

            curl_close($session_request);

            if ($response && json_decode($response, true) === null) {
                $sessionId = trim($response);
            } else {
                $decoded = json_decode($response, true);
                if (!is_array($decoded) || !isset($decoded['session_id'])) {
                    $this->logger->warning('Sizebay session ID API returned invalid response: ' . $response);
                    sleep(1);
                    continue;
                }
                $sessionId = $decoded['session_id'];
            }


            $sessionId = $decoded['session_id'];

            $cookieSet = setcookie('SIZEBAY_SESSION_ID_V4', $sessionId, [
                'expires' => time() + 3600,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);

            if (!$cookieSet) {
                throw new \Exception('Failed to set the session ID cookie.');
            }

            return; // Success, exit method
        }

        throw new \Exception('Invalid response format. Expected JSON object with session_id.');
    }


    private function requestSessionIdValidation()
    {
        if (!isset($_COOKIE["SIZEBAY_SESSION_ID_V4"])) {
            $this->requestSessionID();

            if (!isset($_COOKIE["SIZEBAY_SESSION_ID_V4"])) {
                throw new \Exception('Session ID cookie not set after attempting to generate one');
            }
        }
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
            if ($this->isModuleActive()) {
                $this->requestSessionIdValidation();
            }
        } catch (\Exception $e) {
            $this->logger->error('Error in SizebayTracker observer: ' . $e->getMessage());
        }
    }
}

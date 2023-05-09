<?php

namespace Orangecat\EmailCatch\Plugin\Framework\Mail;

use Magento\Framework\Mail\TransportInterface;
use Orangecat\EmailCatch\Model\Config;
use Orangecat\EmailCatch\Model\LogFactory;

class Transport
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \Orangecat\EmailCatch\Model\Config
     */
    private $config;

    /**
     * @var LogFactory
     */
    private $logFactory;

    /**
     * Constructor
     *
     * @param LogFactory    $logFactory
     * @param Config        $config
     */
    public function __construct(
        LogFactory $logFactory,
        Config $config
    ) {
        $this->config = $config;
        $this->logFactory = $logFactory;
    }

    /**
     * Around Send Message
     *
     * @param TransportInterface $subject
     * @param \Closure $proceed
     * @return object $proceed
     */
    public function aroundSendMessage(
        TransportInterface $subject,
        \Closure $proceed
    ) {
        $message = $subject->getMessage();

        if ($this->config->isRedirectionMailEnable() && $this->config->getEmailAddressRedirect()) {
            $subject = $message->getSubject() . ' ' . __('<<<< REDIRECTED >>>>');
            $message->setSubject($subject);
        }

        if ($this->config->isLogEnabled()) {
            $this->getLoger()->log($message);
        }

        $proceed();
    }

    /**
     * Get Loger
     *
     * @return \Orangecat\EmailCatch\Model\Log
     */
    private function getLoger()
    {
        return $this->logFactory->create();
    }
}

<?php

namespace Orangecat\EmailCatch\Model;

use Orangecat\EmailCatch\Api\Data\LogInterface;
use Orangecat\EmailCatch\Model\Config\Options\Status;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\MailException;
use Magento\Store\Model\Store;

class Sender
{
    public const ADDRESS_SCOPE_FROM = 'from';

    public const ADDRESS_SCOPE_TO = 'to';

    public const RESEND_EMAIL_TEMPLATE_ID = 'mail_resend_template';

    /**
     * @var LogFactory
     */
    private $logFactory;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var Config
     */
    private $config;

    /**
     * Constructor
     *
     * @param LogFactory $logFactory
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param Config $config
     */
    public function __construct(
        \Orangecat\EmailCatch\Model\LogFactory $logFactory,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Orangecat\EmailCatch\Model\Config $config
    ) {
        $this->logFactory = $logFactory;
        $this->transportBuilder = $transportBuilder;
        $this->config = $config;
    }

    /**
     * Send the Email using log Id
     *
     * @param int $logId
     * @return bool
     * @throws MailException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function sendByLogId($logId)
    {
        $log = $this->getCurrentLog($logId);

        if (!$log->getId()) {
            return false;
        }

        $data = $log->getData();
        $data[LogInterface::EMAIL_BODY] = htmlspecialchars_decode($data[LogInterface::EMAIL_BODY]);
        $vars = [];

        if (!$data[LogInterface::EMAIL_BODY]
            || !$data[LogInterface::RECIPIENT_EMAIL]
            || !$data[LogInterface::SENDER_EMAIL]
            || !$data[LogInterface::SUBJECT]
        ) {
            return false;
        }

        $vars[LogInterface::EMAIL_BODY] = quoted_printable_decode($data[LogInterface::EMAIL_BODY]);

        $vars[LogInterface::SUBJECT] = str_replace(' ' . __('<<<< REDIRECTED >>>>'), "", $data[LogInterface::SUBJECT]);

        $this->transportBuilder
            ->addTo($this->prepareEmailsData($data[LogInterface::RECIPIENT_EMAIL], self::ADDRESS_SCOPE_TO))
            ->setFromByScope($this->prepareEmailsData($data[LogInterface::SENDER_EMAIL], self::ADDRESS_SCOPE_FROM));

        if ($data[LogInterface::BCC]) {
            $this->transportBuilder->addBcc($this->prepareEmailsData($data[LogInterface::BCC]));
        }

        if ($data[LogInterface::CC]) {
            $this->transportBuilder->addCc($this->prepareEmailsData($data[LogInterface::CC]));
        }

        try {
            $this->transportBuilder
                ->setTemplateIdentifier(self::RESEND_EMAIL_TEMPLATE_ID)
                ->setTemplateOptions(['store' => Store::DEFAULT_STORE_ID, 'area' => Area::AREA_FRONTEND])
                ->setTemplateVars($vars);

            $this->transportBuilder->getTransport()->sendMessage();

            $log->setData(LogInterface::STATUS, Status::STATUS_SUCCESS)
                ->setData(LogInterface::STATUS_MESSAGE, '')
                ->save();
        } catch (MailException $e) {
            $log->setData(LogInterface::STATUS, Status::STATUS_FAILED)
                ->setData(LogInterface::STATUS_MESSAGE, $e->getMessage())
                ->save();

            return false;
        }

        return true;
    }

    /**
     * Prepare the Data for Send Email
     *
     * @param array $emails
     * @param string $scope
     * @return array|mixed|string|\Zend\Mail\AddressList
     */
    private function prepareEmailsData($emails, $scope = '')
    {
        $emailsConverted = [];
        $emails = explode(',', $emails);
        foreach ($emails as $email) {
            $emailData = explode(' | ', substr($email, 0));

            switch ($scope) {
                case self::ADDRESS_SCOPE_TO:
                    return $emailData[0];
                case self::ADDRESS_SCOPE_FROM:
                    return [
                        'name'  => ($emailData[1] == 'Unknown' ? '' : $emailData[0]),
                        'email' => $emailData[0],
                    ];
            }
            $emailsConverted[] = [
                'name'  => ($emailData[1] == 'Unknown' ? '' : $emailData[0]),
                'email' => $emailData[0],
            ];
        }

        return $this->config->getAddressList($emailsConverted);
    }

    /**
     * Get the Current Log Details using Log Id
     *
     * @param int $logId
     * @return Log
     */
    public function getCurrentLog($logId)
    {
        return $this->logFactory->create()->getLogById($logId);
    }
}

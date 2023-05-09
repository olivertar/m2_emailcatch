<?php

namespace Orangecat\EmailCatch\Model;

use Orangecat\EmailCatch\Api\Data\LogInterface;

class Log extends \Magento\Framework\Model\AbstractModel implements LogInterface
{
    public const LOG_ID_TYPE_FIELD = 'log_id';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var \Orangecat\EmailCatch\Model\Helpers\Date
     */
    private $date;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\Context                          $context
     * @param \Magento\Framework\Registry                               $registry
     * @param \Orangecat\EmailCatch\Model\ResourceModel\Log              $resource
     * @param \Orangecat\EmailCatch\Model\ResourceModel\Log\Collection   $resourceCollection
     * @param \Orangecat\EmailCatch\Model\Config                         $config
     * @param \Orangecat\EmailCatch\Model\Helpers\Date                   $date
     * @param array                                                     $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Orangecat\EmailCatch\Model\ResourceModel\Log $resource,
        \Orangecat\EmailCatch\Model\ResourceModel\Log\Collection $resourceCollection,
        \Orangecat\EmailCatch\Model\Config $config,
        \Orangecat\EmailCatch\Model\Helpers\Date $date,
        array $data = []
    ) {
        $this->config = $config;
        $this->date = $date;
        parent::__construct($context, $registry, $resource, $resourceCollection);
    }

    /**
     * _construct
     */
    public function _construct()
    {
        $this->_init(\Orangecat\EmailCatch\Model\ResourceModel\Log::class);
    }

    /**
     * Get Log Id
     *
     * @return int|mixed
     */
    public function getLogId()
    {
        return $this->getData(LogInterface::LOG_ID);
    }

    /**
     * Set Log Id
     *
     * @param int $logId
     * @return $this|LogInterface
     */
    public function setLogId($logId)
    {
        $this->setData(LogInterface::LOG_ID, $logId);

        return $this;
    }

    /**
     * Get Log By Id
     *
     * @param int $logId
     *
     * @return $this
     */
    public function getLogById($logId)
    {
        $resource = $this->getResource();
        $resource->load($this, $logId);

        return $this;
    }

    /**
     * Before Save
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        parent::beforeSave();

        return $this->updateDate();
    }

    /**
     * Update Date
     *
     * @return $this
     */
    private function updateDate()
    {
        return $this;
    }

    /**
     * Log
     *
     * @param string $message
     * @param array $errorData
     * @return $this
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function log($message, $errorData = [])
    {
        if ($this->config->isLogEnabled()) {
            $logData = $this->getLogData($message);
            $logData[LogInterface::CREATED_AT] = $this->date->getCurrentDate();
            $logData[LogInterface::STATUS] = $errorData ? $errorData[LogInterface::STATUS] : 0;
            $logData[LogInterface::STATUS_MESSAGE] = $errorData ? $errorData[LogInterface::STATUS_MESSAGE] : '';

            $this->setData($logData);
            $this->_resource->save($this);
        }

        return $this;
    }

    /**
     * Get Log Data
     *
     * @param string $message
     * @return array
     */
    private function getLogData($message)
    {
        $result = [];

        $result[LogInterface::SUBJECT] = $message->getSubject() ?: '';
        $result[LogInterface::SENDER_EMAIL] = $this->getEmailsFromAddressList($message->getFrom());
        $result[LogInterface::RECIPIENT_EMAIL] = $this->getEmailsFromAddressList($message->getTo());
        $result[LogInterface::BCC] = $this->getEmailsFromAddressList($message->getBcc());
        $result[LogInterface::CC] = $this->getEmailsFromAddressList($message->getCc());
        $result[LogInterface::EMAIL_BODY] = htmlspecialchars($message->getBodyText());

        return $result;
    }

    /**
     * Get Emails From Address List
     *
     * @param array $emails
     * @return string
     */
    private function getEmailsFromAddressList($emails)
    {
        $result = [];

        if (count($emails)) {
            foreach ($emails as $email) {
                $name = 'Unknown';

                if ($email->getName()) {
                    $name = $email->getName();
                }

                $result[] = $email->getEmail() . " | " . $name . "";
            }
        }

        return implode(',', $result);
    }
}

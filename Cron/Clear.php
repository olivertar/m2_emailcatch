<?php

namespace Orangecat\EmailCatch\Cron;

use Exception;
use Orangecat\EmailCatch\Model\Helpers\Date;
use Orangecat\EmailCatch\Model\Config;
use Orangecat\EmailCatch\Model\ResourceModel\Log\CollectionFactory;
use Psr\Log\LoggerInterface;

class Clear
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Date
     */
    private $date;

    /**
     * Constructor
     *
     * @param LoggerInterface   $logger
     * @param CollectionFactory $collectionFactory
     * @param Date              $date
     * @param Config            $config
     */
    public function __construct(
        LoggerInterface $logger,
        CollectionFactory $collectionFactory,
        Date $date,
        Config $config
    ) {
        $this->logger = $logger;
        $this->date = $date;
        $this->collectionFactory = $collectionFactory;
        $this->config = $config;
    }

    /**
     * Execute
     *
     * @return $this
     */
    public function execute()
    {
        if (!$this->config->enabled()) {
            return $this;
        }

        $cleanDays = $this->config->cleanLogDays();

        if ($cleanDays) {
            $logCollection = $this->collectionFactory->create()
                ->addFieldToFilter('created_at', [
                    'lteq' => $this->date->getCurrentDateBeforeDays($cleanDays)
                ]);

            foreach ($logCollection as $log) {
                try {
                    $log->delete();
                } catch (Exception $e) {
                    $this->logger->critical($e);
                }
            }
        }

        return $this;
    }
}

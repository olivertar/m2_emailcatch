<?php

namespace Orangecat\EmailCatch\Model\ResourceModel\Log;

use Orangecat\EmailCatch\Model\Log;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var \Orangecat\EmailCatch\Model\Date
     */
    private $date;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface         $entityFactory
     * @param \Psr\Log\LoggerInterface                                          $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface      $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface                         $eventManager
     * @param \Orangecat\EmailCatch\Model\Helpers\Date                           $date
     * @param null                                                              $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null         $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Orangecat\EmailCatch\Model\Helpers\Date $date,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->date = $date;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
    }

    /**
     * _construct
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(
            \Orangecat\EmailCatch\Model\Log::class,
            \Orangecat\EmailCatch\Model\ResourceModel\Log::class
        );
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    /**
     * Add Log Id Filter
     *
     * @param string $logId
     * @return $this
     */
    public function addLogIdFilter($logId)
    {
        $this->addFieldToFilter(
            Log::LOG_ID_TYPE_FIELD,
            [
                'eq' => $logId
            ]
        );

        return $this;
    }
}

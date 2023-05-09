<?php

namespace Orangecat\EmailCatch\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Log extends AbstractDb
{
    /**
     *  Smtp Log Store Table Name
     */
    public const EMAIL_LOG_TABLE_NAME = 'email_log';

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(
            self::EMAIL_LOG_TABLE_NAME,
            \Orangecat\EmailCatch\Model\Log::LOG_ID_TYPE_FIELD
        );
    }
}

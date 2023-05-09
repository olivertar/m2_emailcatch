<?php

namespace Orangecat\EmailCatch\Api\Data;

interface LogInterface
{
    /**
     * Constants
     */
    public const TABLE_NAME = 'email_log';

    public const LOG_ID = 'log_id';

    public const CREATED_AT = 'created_at';

    public const SUBJECT = 'subject';

    public const EMAIL_BODY = 'email_body';

    public const SENDER_EMAIL = 'sender_email';

    public const RECIPIENT_EMAIL = 'recipient_email';

    public const CC = 'cc';

    public const BCC = 'bcc';

    public const STATUS = 'status';

    public const STATUS_MESSAGE = 'status_message';

    /**
     * Get log ID
     *
     * @return int
     */
    public function getLogId();

    /**
     * Set Log ID
     *
     * @param int $logId
     * @return LogInterface
     */
    public function setLogId($logId);
}

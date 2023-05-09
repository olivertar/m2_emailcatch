<?php

namespace Orangecat\EmailCatch\Model\Helpers;

class Date
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $date;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Stdlib\DateTime                    $dateTime
     * @param \Magento\Framework\Stdlib\DateTime\DateTime           $date
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface  $timezone
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ) {
        $this->dateTime = $dateTime;
        $this->date = $date;
        $this->timezone = $timezone;
    }

    /**
     * Get Current Date
     *
     * @param bool $includedTime
     *
     * @return null|string
     */
    public function getCurrentDate($includedTime = true)
    {
        return $this->dateTime->formatDate($this->date->gmtTimestamp(), $includedTime);
    }

    /**
     * Get Date From String
     *
     * @param   string $stringTime
     * @param   bool $includedTime
     * @return  null|string
     */
    public function getDateFromString($stringTime, $includedTime = true)
    {
        return $this->dateTime->formatDate($this->dateTime->strToTime($stringTime), $includedTime);
    }

    /**
     * Get Timestamp From String
     *
     * @param string $stringTime
     *
     * @return int
     */
    public function getTimestampFromString($stringTime)
    {
        return $this->dateTime->strToTime($stringTime);
    }

    /**
     * Get Current Date After Days
     *
     * @param int $days
     * @param bool $seconds
     *
     * @return int|null|string
     */
    public function getCurrentDateAfterDays($days, $seconds = false)
    {
        $timestamp = $this->date->gmtTimestamp() + ($days * 24 * 3600);

        return $seconds ? $timestamp : $this->dateTime->formatDate($timestamp);
    }

    /**
     * Get Current Date Before Days
     *
     * @param int $days
     * @param bool $seconds
     *
     * @return int|null|string
     */
    public function getCurrentDateBeforeDays($days, $seconds = false)
    {
        $timestamp = $this->date->gmtTimestamp() - ($days * 24 * 3600);

        return $seconds ? $timestamp : $this->dateTime->formatDate($timestamp);
    }

    /**
     * Get Timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->date->gmtTimestamp();
    }

    /**
     * Get Timezone Date
     *
     * @return string
     */
    public function getTimezoneDate()
    {
        return $this->timezone->formatDate(null, \IntlDateFormatter::SHORT, true);
    }
}

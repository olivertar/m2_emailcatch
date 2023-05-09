<?php

namespace Orangecat\EmailCatch\Model\Config\Options;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    public const STATUS_SUCCESS = 0;

    public const STATUS_FAILED = 1;

    public const STATUS_BLOCKED = 2;

    /**
     * To Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::STATUS_SUCCESS,
                'label' => __('Success')
            ],
            [
                'value' => self::STATUS_FAILED,
                'label' => __('Failed')
            ],
            [
                'value' => self::STATUS_BLOCKED,
                'label' => __('Blocked')
            ]
        ];
    }

    /**
     * Get Label By Status
     *
     * @param string $status
     * @return mixed|string
     */
    public function getLabelByStatus($status)
    {
        foreach ($this->toOptionArray() as $item) {
            if ($item['value'] == $status) {
                return $item['label'];
            }
        }

        return '';
    }
}

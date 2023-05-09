<?php

namespace Orangecat\EmailCatch\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Orangecat\EmailCatch\Model\Config\Options\Status as StatusOptions;

class Status extends Column
{
    /**
     * @var StatusOptions
     */
    private $statusOptions;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface      $context
     * @param \Magento\Framework\View\Element\UiComponentFactory                $uiComponentFactory
     * @param \Orangecat\EmailCatch\Model\Config\Options\Status                  $statusOptions
     * @param array                                                             $components
     * @param array                                                             $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StatusOptions $statusOptions,
        array $components = [],
        array $data = []
    ) {
        $this->statusOptions = $statusOptions;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = $this->getLabelByStatus($item[$this->getData('name')]);
            }
        }

        return $dataSource;
    }

    /**
     * Get Label Satatus
     *
     * @param string $status
     * @return string
     */
    private function getLabelByStatus($status)
    {
        $html = '';
        $label = $this->statusOptions->getLabelByStatus($status);
        switch ($status) {
            case StatusOptions::STATUS_SUCCESS:
                $html = '<span class="grid-severity-notice"><span>'
                    . $label . '</span></span>';
                break;
            case StatusOptions::STATUS_FAILED:
                $html = '<span class="grid-severity-major"><span>'
                    . $label . '</span></span>';
                break;
            default:
                $html = '<span class="grid-severity-minor"><span>'
                    . $label . '</span></span>';
                break;
        }

        return $html;
    }
}

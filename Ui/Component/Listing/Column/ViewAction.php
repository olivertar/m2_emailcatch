<?php

namespace Orangecat\EmailCatch\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ViewAction extends Column
{
    public const PREVIEW_ULR_PATH = 'emailcatch/log/preview';

    public const RESEND_URL_PATH = 'emailcatch/log/resend';

    public const DELETE_LOG_URL_PATH = 'emailcatch/log/delete';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface      $context
     * @param \Magento\Framework\View\Element\UiComponentFactory                $uiComponentFactory
     * @param \Magento\Framework\UrlInterface                                   $urlBuilder
     * @param array                                                             $components
     * @param array                                                             $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
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
                if (isset($item['log_id'])) {
                    $urlEntityParamName = $this->getData('config/urlEntityParamName') ?: 'log_id';
                    $item[$this->getData('name')] = [
                        'preview' => [
                            'href' => $this->context->getUrl(
                                self::PREVIEW_ULR_PATH,
                                [
                                    $urlEntityParamName => $item['log_id']
                                ]
                            ),
                            'target' => '_blank',
                            'label' => __('Preview Email'),
                            'popup' => true,
                        ],
                        'delete' => [
                            'href'    => $this->urlBuilder->getUrl(
                                self::DELETE_LOG_URL_PATH,
                                ['log_id' => $item['log_id']]
                            ),
                            'label'   => __('Delete Email Log'),
                            'confirm' => [
                                'title'   => __('Delete'),
                                'message' => __('Are you sure you want to delete?')
                            ]
                        ],
                        'resend' => [
                            'href'    => $this->urlBuilder->getUrl(
                                self::RESEND_URL_PATH,
                                ['log_id' => $item['log_id']]
                            ),
                            'label'   => __('Resend Email'),
                            'confirm' => [
                                'title'   => __('Resend Email'),
                                'message' => __('Are you sure you want to resend the selected email?')
                            ]
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}

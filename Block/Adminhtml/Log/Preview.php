<?php

namespace Orangecat\EmailCatch\Block\Adminhtml\Log;

class Preview extends \Magento\Backend\Block\Widget
{
    public const LOG_PARAM_URL_KEY = 'log_id';

    /**
     * @var \Orangecat\EmailCatch\Model\LogFactory
     */
    private $logFactory;

    /**
     * Construct
     *
     * @param \Magento\Backend\Block\Template\Context   $context
     * @param \Orangecat\EmailCatch\Model\LogFactory     $logFactory
     * @param array                                     $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Orangecat\EmailCatch\Model\LogFactory $logFactory,
        array $data = []
    ) {
        $this->logFactory = $logFactory;
        parent::__construct($context, $data);
    }

    /**
     * To HTML
     *
     * @return mixed|string
     * @throws \Exception
     */
    protected function _toHtml()
    {
        $logModel = $this->getCurrentLog();
        $string = $logModel->getEmailBody();

        $string = quoted_printable_decode($string);

        if ($logModel->getId()) {
            $out =  '<iframe onload="resizeIframe(this)" srcdoc="'. $string . '" style="width: 100%; height: 100%">';
            $out.= '</iframe>';

            return $out;
        } else {
            throw new \Exception('Log with ID not found. Pleas try again');
        }

        return $logModel;
    }

    /**
     * Get Current Log
     *
     * @return mixed
     */
    public function getCurrentLog()
    {
        return $this->logFactory->create()->getLogById($this->getRequest()->getParam(self::LOG_PARAM_URL_KEY));
    }
}

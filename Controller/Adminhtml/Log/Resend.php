<?php

namespace Orangecat\EmailCatch\Controller\Adminhtml\Log;

class Resend extends \Orangecat\EmailCatch\Controller\Adminhtml\Log
{
    /**
     * @var \Orangecat\EmailCatch\Model\Sender
     */
    private $sender;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context           $context
     * @param \Orangecat\EmailCatch\Model\LogFactory         $ruleFactory
     * @param \Magento\Framework\Registry                   $registry
     * @param \Magento\Framework\View\Result\PageFactory    $resultPageFactory
     * @param \Orangecat\EmailCatch\Model\Sender             $sender
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Orangecat\EmailCatch\Model\LogFactory $ruleFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Orangecat\EmailCatch\Model\Sender $sender
    ) {
        parent::__construct($context, $ruleFactory, $registry, $resultPageFactory);
        $this->sender = $sender;
    }

    /**
     * Execute
     */
    public function execute()
    {
        $logId = $this->getRequest()->getParam('log_id');

        if ($logId) {
            try {
                if ($this->sender->sendByLogId($logId)) {
                    $this->messageManager->addSuccessMessage(__('Email successfully was send.'));
                } else {
                    $this->messageManager->addErrorMessage(__('Something went wrong.'));
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong. See the error log.'));
            }
        } else {
            $this->messageManager->addErrorMessage(__('Unable to find the rule'));
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}

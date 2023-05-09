<?php

namespace Orangecat\EmailCatch\Controller\Adminhtml\Log;

class Delete extends \Orangecat\EmailCatch\Controller\Adminhtml\Log
{
    /**
     * Execute
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getParams()) {
            $rule = $this->getRule();

            try {
                if ($rule->getId()) {
                    $rule->delete();
                    $this->messageManager->addSuccessMessage(
                        __('Email Log has been successfully deleted')
                    );
                } else {
                    $this->messageManager->addErrorMessage(__('Unable to find the rule'));
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong. See the error log.'));
            }
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}

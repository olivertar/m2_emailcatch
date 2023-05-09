<?php

namespace Orangecat\EmailCatch\Controller\Adminhtml\Log;

class Index extends \Orangecat\EmailCatch\Controller\Adminhtml\Log
{
    /**
     * Execute
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->initPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Emails Log'));

        return $resultPage;
    }
}

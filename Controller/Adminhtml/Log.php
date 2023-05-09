<?php

namespace Orangecat\EmailCatch\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Log extends Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /** @var \Magento\Framework\Registry */
    protected $registry;

    /** @var \Orangecat\EmailCatch\Model\RuleFactory */
    protected $ruleFactory;

    /**
     * Constructor.
     *
     * @param Orangecat\Context                              $context
     * @param \Orangecat\EmailCatch\Model\RuleFactory        $ruleFactory
     * @param \Magento\Framework\Registry                   $registry
     * @param \Magento\Framework\View\Result\PageFactory    $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Orangecat\EmailCatch\Model\LogFactory $ruleFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Init Page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Orangecat_EmailCatch::log');
        $resultPage->addBreadcrumb(__('Reports'), __('Reports'));
        return $resultPage;
    }

    /**
     * Get rule
     *
     * @param bool $requireId
     * @return \Orangecat\EmailCatch\Model\Log|\Magento\Framework\App\ResponseInterface
     */
    protected function getRule($requireId = false)
    {
        $ruleId = $this->getRequest()->getParam('log_id');
        if ($requireId && !$ruleId) {
            $this->messageManager->addErrorMessage(__('Log doesn\'t exist.'));
            return $this->redirectIndex();
        }

        $model = $this->ruleFactory->create();

        if ($ruleId) {
            $model->load($ruleId);
        }

        if ($ruleId && !$model->getId()) {
            $this->messageManager->addErrorMessage(__('Log doesn\'t exist.'));
            return $this->redirectIndex();
        }

        $this->registry->register('current_email_log', $model);

        return $model;
    }

    /**
     * Redirect Index
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    protected function redirectIndex()
    {
        return $this->_redirect('*/*/');
    }

    /**
     * Is Allow
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Orangecat_EmailCatch::log');
    }
}

<?php

namespace Orangecat\EmailCatch\Controller\Adminhtml\Log;

use Orangecat\EmailCatch\Model\ResourceModel\Log\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Orangecat\EmailCatch\Model\Sender;
use Magento\Framework\Registry;

class MassResend extends \Magento\Backend\App\Action
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Sender
     */
    private $sender;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * Constructor
     *
     * @param Context           $context
     * @param Filter            $filter
     * @param Sender            $sender
     * @param Registry          $registry
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        Sender $sender,
        Registry $registry,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->registry = $registry;
        $this->sender = $sender;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $resCount = 0;

        if ($collection->getSize()) {
            $this->registry->register('current_email_log', true);
        }

        foreach ($collection as $item) {
            if ($this->sender->sendByLogId($item->getLogId())) {
                $resCount++;
            }
        }

        $this->messageManager->addSuccess(__('A total of %1 email(s) have been sent.', $resCount));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('emailcatch/*/index');
    }
}

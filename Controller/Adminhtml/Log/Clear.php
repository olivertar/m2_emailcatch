<?php

namespace Orangecat\EmailCatch\Controller\Adminhtml\Log;

use Orangecat\EmailCatch\Model\ResourceModel\Log\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Clear extends \Magento\Backend\App\Action
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Constructor
     *
     * @param Context           $context
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory
    ) {
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
        $collection     = $this->collectionFactory->create();
        $items = $collection->getItems();
        $count = count($items);

        if ($items) {
            foreach ($items as $item) {
                $item->delete();
            }
        }

        $this->messageManager->addSuccess(__('A total of %1 email(s) have been deleted.', $count));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('emailcatch/*/index');
    }
}

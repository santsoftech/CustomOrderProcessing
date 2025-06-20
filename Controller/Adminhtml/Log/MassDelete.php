<?php
namespace Vendor\CustomOrderProcessing\Controller\Adminhtml\Log;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Vendor\CustomOrderProcessing\Model\ResourceModel\Log\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'Vendor_CustomOrderProcessing::Log_delete';

    protected $filter;
    protected $collectionFactory;

    public function __construct(
        Action\Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $deleted = 0;
        foreach ($collection as $item) {
            $item->delete();
            $deleted++;
        }
        if ($deleted) {
            $this->messageManager->addSuccessMessage(__('%1 log(s) deleted.', $deleted));
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
<?php
namespace Vendor\CustomOrderProcessing\Ui\Component\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Vendor\CustomOrderProcessing\Model\ResourceModel\Log\CollectionFactory as LogCollectionFactory;

class LogDataProvider extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        LogCollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();        
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
}
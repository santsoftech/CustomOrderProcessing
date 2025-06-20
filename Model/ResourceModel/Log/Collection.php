<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Model\ResourceModel\Log;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'log_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Vendor\CustomOrderProcessing\Model\Log::class,
            \Vendor\CustomOrderProcessing\Model\ResourceModel\Log::class
        );
    }
}


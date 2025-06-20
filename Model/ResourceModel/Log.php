<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Log extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('vendor_customorderprocessing_log', 'log_id');
    }
}


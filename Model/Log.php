<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\CustomOrderProcessing\Api\Data\LogInterface;

class Log extends AbstractModel implements LogInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Vendor\CustomOrderProcessing\Model\ResourceModel\Log::class);
    }

    /**
     * @inheritDoc
     */
    public function getLogId()
    {
        return $this->getData(self::LOG_ID);
    }

    /**
     * @inheritDoc
     */
    public function setLogId($logId)
    {
        return $this->setData(self::LOG_ID, $logId);
    }

    /**
     * @inheritDoc
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @inheritDoc
     */
    public function getOldStatus()
    {
        return $this->getData(self::OLD_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setOldStatus($oldStatus)
    {
        return $this->setData(self::OLD_STATUS, $oldStatus);
    }

    /**
     * @inheritDoc
     */
    public function getNewStatus()
    {
        return $this->getData(self::NEW_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setNewStatus($newStatus)
    {
        return $this->setData(self::NEW_STATUS, $newStatus);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}


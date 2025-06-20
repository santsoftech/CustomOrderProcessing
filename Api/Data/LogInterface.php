<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Api\Data;

interface LogInterface
{

    const NEW_STATUS = 'new_status';
    const OLD_STATUS = 'old_status';
    const ORDER_ID = 'order_id';
    const CREATED_AT = 'created_at';
    const LOG_ID = 'log_id';

    /**
     * Get log_id
     * @return string|null
     */
    public function getLogId();

    /**
     * Set log_id
     * @param string $logId
     * @return \Vendor\CustomOrderProcessing\Log\Api\Data\LogInterface
     */
    public function setLogId($logId);

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param string $orderId
     * @return \Vendor\CustomOrderProcessing\Log\Api\Data\LogInterface
     */
    public function setOrderId($orderId);

    /**
     * Get old_status
     * @return string|null
     */
    public function getOldStatus();

    /**
     * Set old_status
     * @param string $oldStatus
     * @return \Vendor\CustomOrderProcessing\Log\Api\Data\LogInterface
     */
    public function setOldStatus($oldStatus);

    /**
     * Get new_status
     * @return string|null
     */
    public function getNewStatus();

    /**
     * Set new_status
     * @param string $newStatus
     * @return \Vendor\CustomOrderProcessing\Log\Api\Data\LogInterface
     */
    public function setNewStatus($newStatus);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Vendor\CustomOrderProcessing\Log\Api\Data\LogInterface
     */
    public function setCreatedAt($createdAt);
}


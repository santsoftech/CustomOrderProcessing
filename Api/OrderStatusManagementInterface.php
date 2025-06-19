<?php
/**
 * Interface for managing order status updates via API.
 */
namespace Vendor\CustomOrderProcessing\Api;

interface OrderStatusManagementInterface
{
    /**
     * Update order status by increment ID
     * @param string $incrementId
     * @param string $status
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateStatus($incrementId, $status);
}
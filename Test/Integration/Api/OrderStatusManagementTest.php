<?php
namespace Vendor\CustomOrderProcessing\Test\Integration\Api;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class OrderStatusManagementTest extends TestCase
{
    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testUpdateStatus()
    {
        $objectManager = Bootstrap::getObjectManager();
        $api = $objectManager->get(\Vendor\CustomOrderProcessing\Api\OrderStatusManagementInterface::class);

        $orderRepository = $objectManager->get(\Magento\Sales\Api\OrderRepositoryInterface::class);
        $order = $orderRepository->get(1); // Assumes order fixture ID is 1

        $result = $api->updateStatus($order->getIncrementId(), 'processing');
        $this->assertEquals('processing', $result->getStatus());
    }

    public function testUpdateStatusThrowsExceptionIfOrderNotFound()
    {
        $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
        $objectManager = Bootstrap::getObjectManager();
        $api = $objectManager->get(\Vendor\CustomOrderProcessing\Api\OrderStatusManagementInterface::class);

        // Attempt to update a non-existent order
        $api->updateStatus('non-existent-order-id', 'processing');
    }

    public function testUpdateStatusThrowsExceptionIfStatusIsInvalid()
    {
        $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
        $objectManager = Bootstrap::getObjectManager();
        $api = $objectManager->get(\Vendor\CustomOrderProcessing\Api\OrderStatusManagementInterface::class);

        // Attempt to update an order with an invalid status
        $api->updateStatus('100000001', 'invalid_status');
    }

}

    
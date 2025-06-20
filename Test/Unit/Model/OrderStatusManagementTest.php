<?php
namespace Vendor\CustomOrderProcessing\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Vendor\CustomOrderProcessing\Model\OrderStatusManagement;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DB\Transaction;
use Vendor\CustomOrderProcessing\Helper\Data as CustomHelper;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchResults;

class OrderStatusManagementTest extends TestCase
{
    
public function testUpdateStatusSuccess()
{
    $orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
    $searchCriteriaBuilder = $this->createMock(\Magento\Framework\Api\SearchCriteriaBuilder::class);
    $transaction = $this->createMock(\Magento\Framework\DB\Transaction::class);
    $customHelper = $this->createMock(\Vendor\CustomOrderProcessing\Helper\Data::class);
    $logger = $this->createMock(\Psr\Log\LoggerInterface::class);

    $searchCriteria = $this->createMock(\Magento\Framework\Api\SearchCriteria::class);
    $searchResults = $this->createMock(\Magento\Framework\Api\SearchResults::class);

    $order = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['getConfig', 'getState', 'getStatus', 'setStatus'])
        ->getMock();

    $orderConfig = $this->getMockBuilder(\Magento\Sales\Model\Order\Config::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['getStateStatuses'])
        ->getMock();

    $searchCriteriaBuilder->method('addFilter')->willReturnSelf();
    $searchCriteriaBuilder->method('create')->willReturn($searchCriteria);
    $orderRepository->method('getList')->willReturn($searchResults);
    $searchResults->method('getItems')->willReturn([$order]);

    $order->method('getConfig')->willReturn($orderConfig);
    $order->method('getState')->willReturn('processing');
    $order->method('getStatus')->willReturn('pending');
    $orderConfig->method('getStateStatuses')->with('processing')->willReturn(['processing' => 'Processing', 'complete' => 'Complete']);

    $customHelper->method('isStatusChangeNotAllowed')->willReturn(false);

    $order->expects($this->once())->method('setStatus')->with('processing');
    $orderRepository->expects($this->once())->method('save')->with($order);

    $model = new \Vendor\CustomOrderProcessing\Model\OrderStatusManagement(
        $orderRepository,
        $searchCriteriaBuilder,
        $transaction,
        $customHelper,
        $logger
    );

    $result = $model->updateStatus('100000003', 'processing');
    $this->assertSame($order, $result);
}

public function testUpdateStatusThrowsExceptionIfStatusIsInvalid()
{
    $orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
    $searchCriteriaBuilder = $this->createMock(\Magento\Framework\Api\SearchCriteriaBuilder::class);
    $transaction = $this->createMock(\Magento\Framework\DB\Transaction::class);
    $customHelper = $this->createMock(\Vendor\CustomOrderProcessing\Helper\Data::class);
    $logger = $this->createMock(\Psr\Log\LoggerInterface::class);

    $searchCriteria = $this->createMock(\Magento\Framework\Api\SearchCriteria::class);
    $searchResults = $this->createMock(\Magento\Framework\Api\SearchResults::class);

    $order = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['getConfig', 'getState', 'getStatus'])
        ->getMock();

    $orderConfig = $this->getMockBuilder(\Magento\Sales\Model\Order\Config::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['getStateStatuses'])
        ->getMock();

    $searchCriteriaBuilder->method('addFilter')->willReturnSelf();
    $searchCriteriaBuilder->method('create')->willReturn($searchCriteria);
    $orderRepository->method('getList')->willReturn($searchResults);
    $searchResults->method('getItems')->willReturn([$order]);

    $order->method('getConfig')->willReturn($orderConfig);
    $order->method('getState')->willReturn('processing');
    $order->method('getStatus')->willReturn('pending');
    $orderConfig->method('getStateStatuses')->with('processing')->willReturn(['processing' => 'Processing', 'complete' => 'Complete']);

    $customHelper->method('isStatusChangeNotAllowed')->willReturn(false);

    $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
    $this->expectExceptionMessage('Status "invalid_status" is not allowed for the current order state "processing".');

    $model = new \Vendor\CustomOrderProcessing\Model\OrderStatusManagement(
        $orderRepository,
        $searchCriteriaBuilder,
        $transaction,
        $customHelper,
        $logger
    );

    $model->updateStatus('100000004', 'invalid_status');
}

public function testUpdateStatusThrowsExceptionIfOrderNotFound()
{
    $orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
    $searchCriteriaBuilder = $this->createMock(\Magento\Framework\Api\SearchCriteriaBuilder::class);
    $transaction = $this->createMock(\Magento\Framework\DB\Transaction::class);
    $customHelper = $this->createMock(\Vendor\CustomOrderProcessing\Helper\Data::class);
    $logger = $this->createMock(\Psr\Log\LoggerInterface::class);

    $searchCriteria = $this->createMock(\Magento\Framework\Api\SearchCriteria::class);
    $searchResults = $this->createMock(\Magento\Framework\Api\SearchResults::class);

    $searchCriteriaBuilder->method('addFilter')->willReturnSelf();
    $searchCriteriaBuilder->method('create')->willReturn($searchCriteria);
    $orderRepository->method('getList')->willReturn($searchResults);
    $searchResults->method('getItems')->willReturn([]); // No orders found

    $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
    $this->expectExceptionMessage('Order not found for increment ID: 100000005');

    $model = new \Vendor\CustomOrderProcessing\Model\OrderStatusManagement(
        $orderRepository,
        $searchCriteriaBuilder,
        $transaction,
        $customHelper,
        $logger
    );

    $model->updateStatus('100000005', 'processing');
}    
   
public function testUpdateStatusThrowsExceptionIfStatusIsNotAllowedForState()
{
    $orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
    $searchCriteriaBuilder = $this->createMock(\Magento\Framework\Api\SearchCriteriaBuilder::class);
    $transaction = $this->createMock(\Magento\Framework\DB\Transaction::class);
    $customHelper = $this->createMock(\Vendor\CustomOrderProcessing\Helper\Data::class);
    $logger = $this->createMock(\Psr\Log\LoggerInterface::class);

    $searchCriteria = $this->createMock(\Magento\Framework\Api\SearchCriteria::class);
    $searchResults = $this->createMock(\Magento\Framework\Api\SearchResults::class);

    // Mock order as concrete class to allow getConfig() and getStateStatuses()
    $order = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['getConfig', 'getState', 'getStatus', 'setStatus'])
        ->getMock();

    $orderConfig = $this->getMockBuilder(\Magento\Sales\Model\Order\Config::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['getStateStatuses'])
        ->getMock();

    $searchCriteriaBuilder->method('addFilter')->willReturnSelf();
    $searchCriteriaBuilder->method('create')->willReturn($searchCriteria);
    $orderRepository->method('getList')->willReturn($searchResults);
    $searchResults->method('getItems')->willReturn([$order]);

    $order->method('getConfig')->willReturn($orderConfig);
    $order->method('getState')->willReturn('processing');
    $order->method('getStatus')->willReturn('pending');
    $orderConfig->method('getStateStatuses')->with('processing')->willReturn(['processing' => 'Processing', 'complete' => 'Complete']);

    $customHelper->method('isStatusChangeNotAllowed')->willReturn(false);

    $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
    $this->expectExceptionMessage('Status "invalid_status" is not allowed for the current order state "processing".');

    $model = new \Vendor\CustomOrderProcessing\Model\OrderStatusManagement(
        $orderRepository,
        $searchCriteriaBuilder,
        $transaction,
        $customHelper,
        $logger
    );

    $model->updateStatus('100000002', 'invalid_status');
}
}
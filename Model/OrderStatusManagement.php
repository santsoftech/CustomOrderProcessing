<?php
namespace Vendor\CustomOrderProcessing\Model;

use Vendor\CustomOrderProcessing\Api\OrderStatusManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DB\Transaction;
use Vendor\CustomOrderProcessing\Helper\Data as CustomHelper;
use Psr\Log\LoggerInterface;

class OrderStatusManagement implements OrderStatusManagementInterface
{
    protected $orderRepository;
    protected $searchCriteriaBuilder;
    protected $transaction;
    protected $customHelper;
    protected $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Transaction $transaction,
        CustomHelper $customHelper,
        LoggerInterface $logger // Add logger for error logging
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->transaction = $transaction;
        $this->customHelper = $customHelper;
        $this->logger = $logger;
    }

    /**
     * Update the status of an order by increment ID.
     *
     * @param string $incrementId The increment ID of the order.
     * @param string $status The new status to set.
     * @return \Magento\Sales\Api\Data\OrderInterface The updated order object.
     * @throws LocalizedException If the order is not found or the status is invalid.
     */
    public function updateStatus($incrementId, $status)
    {
        try {
            // Validate input
            if (empty($incrementId)) {
                throw new LocalizedException(__('Order increment ID is required.'));
            }
            if (empty($status)) {
                throw new LocalizedException(__('Order status is required.'));
            }

            // Find order by increment ID
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('increment_id', $incrementId)
                ->create();
            $orders = $this->orderRepository->getList($searchCriteria)->getItems();

            if (empty($orders)) {
                throw new LocalizedException(__('Order not found for increment ID: %1', $incrementId));
            }

            /** @var \Magento\Sales\Api\Data\OrderInterface $order */
            $order = array_shift($orders);

            // Validate status transition for the current state
            $allowedStatuses = $order->getConfig()->getStateStatuses($order->getState());
            if (!isset($allowedStatuses[$status])) {
                throw new LocalizedException(__('Status "%1" is not allowed for the current order state "%2".', $status, $order->getState()));
            }

            // Custom business rule validation
            if ($this->customHelper->isStatusChangeNotAllowed($order, $status)) {
                throw new LocalizedException(__('Status change to "%1" is not allowed for this order.', $status));
            }

            // Only update if status is actually changing
            if ($order->getStatus() === $status) {
                throw new LocalizedException(__('Order is already in status "%1".', $status));
            }

            $order->setStatus($status);
            $this->orderRepository->save($order);

            return $order;
        } catch (LocalizedException $e) {
            // Rethrow known exceptions
            throw $e;
        } catch (\Exception $e) {
            // Log and throw a generic error for unexpected exceptions
            $this->logger->error('Order status update error: ' . $e->getMessage(), [
                'increment_id' => $incrementId,
                'status' => $status
            ]);
            throw new LocalizedException(__('An unexpected error occurred while updating the order status.'));
        }
    }
}
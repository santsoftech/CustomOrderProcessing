<?php
/**
 * Class OrderStatusManagement
 *
 * Implements the API for updating order status.
 */
namespace Vendor\CustomOrderProcessing\Model;

use Vendor\CustomOrderProcessing\Api\OrderStatusManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DB\Transaction;
use Vendor\CustomOrderProcessing\Helper\Data as CustomHelper;

class OrderStatusManagement implements OrderStatusManagementInterface
{
    protected $orderRepository;
    protected $searchCriteriaBuilder;
    protected $transaction;
    protected $customHelper;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Transaction $transaction,
        CustomHelper $customHelper
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->transaction = $transaction;
        $this->customHelper = $customHelper;
    }

        /**
     * Update the status of an order by increment ID.
     *
     * Validates the order existence and the requested status transition.
     *
     * @param string $incrementId The increment ID of the order.
     * @param string $status The new status to set.
     * @return \Magento\Sales\Api\Data\OrderInterface The updated order object.
     * @throws LocalizedException If the order is not found or the status is invalid.
     */
    public function updateStatus($incrementId, $status)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $incrementId)
            ->create();
        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        if (empty($orders)) {
            throw new LocalizedException(__('Order not found.'));
        }

        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        $order = array_shift($orders);

        // Validate status transition
        $allowedStatuses = $order->getConfig()->getStatuses();
        if (!isset($allowedStatuses[$status])) {
            throw new LocalizedException(__('Invalid order status.'));
        }

        if ($this->customHelper->isStatusChangeNotAllowed($order, $status)) {
            throw new LocalizedException(__('Status change is not allowed for this order.'));
        }

        $order->setStatus($status);
        $this->orderRepository->save($order);
        
        return $order;
    }
}
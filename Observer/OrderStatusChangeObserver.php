<?php
/**
 * Observer for order status changes.
 *
 * Listens to the sales_order_save_after event, logs status changes,
 * and sends an email notification if the order is marked as shipped.
 */
namespace Vendor\CustomOrderProcessing\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Vendor\CustomOrderProcessing\Helper\Data as CustomHelper;
use Magento\Framework\App\ResourceConnection;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;


class OrderStatusChangeObserver implements ObserverInterface
{
    protected $resource;
    protected $orderRepository;
    protected $transportBuilder;
    protected $storeManager;
    protected $customHelper;

    public function __construct(
        ResourceConnection $resource,
        OrderRepositoryInterface $orderRepository,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        CustomHelper $customHelper
    ) {
        $this->resource = $resource;
        $this->orderRepository = $orderRepository;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->customHelper = $customHelper;
    }

     /**
     * Execute observer logic on order save.
     *
     * Logs the status change to a custom table and sends an email if shipped.
     *
     * @param Observer $observer The event observer object.
     * @return void
     */

    public function execute(Observer $observer)
    {
        
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        $origData = $order->getOrigData();
        $oldStatus = $origData['status'] ?? null;
        $newStatus = $order->getStatus();                
        if ($oldStatus !== $newStatus) {
            // Log to custom table
            $connection = $this->resource->getConnection();
            $connection->insert(
                $this->resource->getTableName('vendor_customorderprocessing_log'),
                [
                    'order_id' => $order->getId(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
                ]
            );

            // If shipped, send email
            if ($newStatus === 'complete') {
                $this->customHelper->log('email sent');   
        
                $this->sendShippedEmail($order);
            }
        }
    }

    /**
     * Send an email notification to the customer when the order is shipped.
     *
     * Uses a dynamic email template identifier.
     *
     * @param \Magento\Sales\Model\Order $order The order object.
     * @param string $templateIdentifier The email template identifier (default: 'order_shipped_notification').
     * @return void
     */
    protected function sendShippedEmail($order)
{
    try {
        $customerEmail = $order->getCustomerEmail();
        $customerName = $order->getCustomerName();
        $storeId = $order->getStoreId();

        $templateVars = [
            'order' => $order,
            'customer_name' => $customerName,
            'increment_id' => $order->getIncrementId(),
        ];

        $sender = [
            'name' => $this->storeManager->getStore($storeId)->getFrontendName(),
            'email' => 'no-reply@' . parse_url($this->storeManager->getStore($storeId)->getBaseUrl(), PHP_URL_HOST),
        ];

        // Render the email body
        $templateId = 'vendor_order_shipped_notification';
        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId,
        ];

        $transport = $this->transportBuilder
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($sender)
            ->addTo($customerEmail, $customerName)
            ->getTransport();

        $transport->sendMessage();

    } catch (\Exception $e) {
        $this->customHelper->log('Email sending failed: ' . $e->getMessage());
    }
}
}
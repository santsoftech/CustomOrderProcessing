<?php
namespace Vendor\CustomOrderProcessing\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Psr\Log\LoggerInterface;
use Magento\Sales\Api\Data\OrderInterface;

class Data extends AbstractHelper
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->logger = $logger;
    }

    /**
     * Log a message to var/log/system.log
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($message, array $context = [])
    {
        $this->logger->info($message, $context);
    }

    /**
     * Validate if the given status is NOT allowed for the order according to Magento OOTB flow.
     *
     * @param OrderInterface $order
     * @param string $status
     * @return bool True if status is NOT allowed, false if allowed
     */
    public function isStatusChangeNotAllowed(OrderInterface $order, $status)
    {
        $state = $order->getState();
        $allowedStatuses = $order->getConfig()->getStateStatuses($state);

        return !isset($allowedStatuses[$status]);
    }
}
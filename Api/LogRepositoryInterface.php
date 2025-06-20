<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface LogRepositoryInterface
{

    /**
     * Save Log
     * @param \Vendor\CustomOrderProcessing\Api\Data\LogInterface $log
     * @return \Vendor\CustomOrderProcessing\Api\Data\LogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Vendor\CustomOrderProcessing\Api\Data\LogInterface $log
    );

    /**
     * Retrieve Log
     * @param string $logId
     * @return \Vendor\CustomOrderProcessing\Api\Data\LogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($logId);

    /**
     * Retrieve Log matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Vendor\CustomOrderProcessing\Api\Data\LogSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Log
     * @param \Vendor\CustomOrderProcessing\Api\Data\LogInterface $log
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Vendor\CustomOrderProcessing\Api\Data\LogInterface $log
    );

    /**
     * Delete Log by ID
     * @param string $logId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($logId);
}


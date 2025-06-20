<?php
namespace Vendor\CustomOrderProcessing\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class StatusOptions implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'pending', 'label' => __('Pending')],
            ['value' => 'processing', 'label' => __('Processing')],
            ['value' => 'complete', 'label' => __('Complete')],
            ['value' => 'canceled', 'label' => __('Canceled')],            
        ];
    }

    public function toArray()
    {
        $options = [];
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }
}
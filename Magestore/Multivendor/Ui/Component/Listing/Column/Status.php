<?php

namespace Magestore\Multivendor\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * @package Magestore\Multivendor\Ui\Component\Listing\Column
 */
class Status implements OptionSourceInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('Enabled'), 'value' => 1],
            ['label' => __('Disabled'), 'value' => 2],
        ];
    }
}

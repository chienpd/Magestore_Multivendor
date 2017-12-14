<?php

namespace Magestore\Multivendor\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * Class VendorProduct
 * @package Magestore\Multivendor\Model
 */
class VendorProduct extends AbstractModel
{

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ResourceModel\VendorProduct $resource
     * @param ResourceModel\VendorProduct\Collection $resourceCollection
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ResourceModel\VendorProduct $resource,
        ResourceModel\VendorProduct\Collection $resourceCollection
    )
    {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }
}

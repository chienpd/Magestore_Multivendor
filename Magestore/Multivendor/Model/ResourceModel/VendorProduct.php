<?php

namespace Magestore\Multivendor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class VendorProduct
 * @package Magestore\Multivendor\Model\ResourceModel
 */
class VendorProduct extends AbstractDb
{

    /**
     *
     */
    protected function _construct()
    {

        $this->_init('multivendor_vendor_product', 'vendor_product_id');
    }
}
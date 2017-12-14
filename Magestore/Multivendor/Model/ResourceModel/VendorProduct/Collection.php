<?php

namespace Magestore\Multivendor\Model\ResourceModel\VendorProduct;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Magestore\Multivendor\Model\ResourceModel\VendorProduct
 */
class Collection extends AbstractCollection
{
    /**
     *
     */
    public function _construct()
    {
        ;
        parent::_construct();
        $this->_init('Magestore\Multivendor\Model\VendorProduct', 'Magestore\Multivendor\Model\ResourceModel\VendorProduct');
    }
}
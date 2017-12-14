<?php

namespace Magestore\Multivendor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Vendor
 * @package Magestore\Multivendor\Model\ResourceModel
 */
class Vendor extends AbstractDb
{

    /**
     *
     */
    protected function _construct()
    {

        $this->_init('multivendor_vendor', 'vendor_id');
    }
}
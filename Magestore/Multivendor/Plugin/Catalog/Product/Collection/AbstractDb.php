<?php

namespace Magestore\Multivendor\Plugin\Catalog\Product\Collection;
/**
 * Class AbstractDb
 * @package Magestore\Multivendor\Plugin\Catalog\Product\Collection
 */
class AbstractDb extends \Magento\Framework\Data\Collection\AbstractDb
{
    /**
     * @param \Magento\Framework\Data\Collection\AbstractDb $object
     */
    public function beforeGetSize(\Magento\Framework\Data\Collection\AbstractDb $object)
    {
        $object->_totalRecords = null;
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    public function getResource()
    {
        return parent::getResource();
    }
}
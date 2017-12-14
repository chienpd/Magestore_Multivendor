<?php

namespace Magestore\Multivendor\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

/**
 * Class Vendor
 * @package Magestore\Multivendor\Model
 */
class Vendor extends AbstractModel
{
    /**
     * @var DateTimeFactory
     */
    protected $_dateFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ResourceModel\Vendor $resource
     * @param ResourceModel\Vendor\Collection $resourceCollection
     * @param DateTimeFactory $dateFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ResourceModel\Vendor $resource,
        ResourceModel\Vendor\Collection $resourceCollection,
        DateTimeFactory $dateFactory
    )
    {
        $this->_dateFactory = $dateFactory;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }

    /**
     * @return $this
     */
    public function beforeSave()
    {
        if (!$this->getId()) {
            $this->setCreatedAt($this->_dateFactory->create()->gmtDate());
        } else {
            $this->setUpdatedAt($this->_dateFactory->create()->gmtDate());
        }

        return parent::beforeSave();
    }

}

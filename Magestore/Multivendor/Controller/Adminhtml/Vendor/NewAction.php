<?php

namespace Magestore\Multivendor\Controller\Adminhtml\Vendor;

use Magento\Framework\Controller\ResultFactory;
use Magestore\Multivendor\Controller\Adminhtml\Vendor;

/**
 * Class NewAction
 * @package Magestore\Multivendor\Controller\Adminhtml\Vendor
 */
class NewAction extends Vendor
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        return $resultForward->forward('edit');
    }
}

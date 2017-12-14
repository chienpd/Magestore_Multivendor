<?php

namespace Magestore\Multivendor\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

/**
 * Class Vendor
 * @package Magestore\Multivendor\Controller\Adminhtml
 */
abstract class Vendor extends Action
{
    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        /** @var TYPE_NAME $context */
        parent::__construct($context);
    }


    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_Multivendor::vendor_manage');
    }
}
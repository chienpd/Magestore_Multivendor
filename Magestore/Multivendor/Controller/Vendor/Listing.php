<?php

namespace Magestore\Multivendor\Controller\Vendor;

use Magento\Framework\App\Action\Action;

/**
 * Class Listing
 * @package Magestore\Multivendor\Controller\Vendor
 */
class Listing extends Action
{
    /**
     *
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
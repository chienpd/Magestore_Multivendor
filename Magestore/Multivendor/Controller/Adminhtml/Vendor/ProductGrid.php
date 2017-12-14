<?php

namespace Magestore\Multivendor\Controller\Adminhtml\Vendor;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\LayoutFactory;
use Magestore\Multivendor\Controller\Adminhtml\Vendor;

/**
 * Class ProductGrid
 * @package Magestore\Multivendor\Controller\Adminhtml\Vendor
 */
class ProductGrid extends Vendor
{
    /**
     * @var LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * @param Context $context
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Context $context,
        LayoutFactory $resultLayoutFactory
    )
    {
        $this->_resultLayoutFactory = $resultLayoutFactory;
        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('vendor.edit.tab.product')
            ->setProductsVendor($this->getRequest()->getPost('products_vendor', null));
        return $resultLayout;
    }
}

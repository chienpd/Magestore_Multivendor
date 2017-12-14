<?php

namespace Magestore\Multivendor\Controller\Vendor;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class View
 * @package Magestore\Multivendor\Controller\Vendor
 */
class View extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if (!$this->_coreRegistry->registry('current_category')) {
            $category = $this->_objectManager
                ->create('Magento\Catalog\Model\Category')
                ->load($this->_storeManager->getStore()->getRootCategoryId());
            $this->_coreRegistry->register('current_category', $category);
        }
        $resultPage = $this->resultPageFactory->create();
        $id = $this->getRequest()->getParam('id');
        $vendorModel = $this->_objectManager
            ->create('Magestore\Multivendor\Model\Vendor')
            ->load($id);
        $resultPage->addHandle('catalog_category_view');
        $resultPage->getConfig()->getTitle()->set($vendorModel->getName());
        return $resultPage;
    }
}
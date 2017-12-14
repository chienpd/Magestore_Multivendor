<?php

namespace Magestore\Multivendor\Controller\Adminhtml\Vendor;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magestore\Multivendor\Controller\Adminhtml\Vendor;

/**
 * Class Edit
 * @package Magestore\Multivendor\Controller\Adminhtml\Vendor
 */
class Edit extends Vendor
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory

    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }


    /**
     * @return $this|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->_objectManager->create('Magestore\Multivendor\Model\Vendor');
        $registryObject = $this->_objectManager->get('Magento\Framework\Registry');
        if ($id) {
            $model = $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This vendor no longer exists.'));
                return $resultRedirect->setPath('multivendoradmin/*/', ['_current' => true]);
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }
        $registryObject->register('current_vendor', $model);

        $resultPage = $this->_resultPageFactory->create();
        if (!$model->getId()) {
            $pageTitle = __('New Vendor');
        } else {
            $pageTitle = __('Edit Vendor %1', $model->getName());
        }

        $resultPage->getConfig()->getTitle()->prepend($pageTitle);
        return $resultPage;
    }

}

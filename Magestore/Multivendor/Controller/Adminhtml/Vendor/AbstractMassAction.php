<?php

namespace Magestore\Multivendor\Controller\Adminhtml\Vendor;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magestore\Multivendor\Model\ResourceModel\Vendor\CollectionFactory;
use Magestore\Multivendor\Model\ResourceModel\Vendor\Collection;

/**
 * Class AbstractMassAction
 * @package Magestore\Multivendor\Controller\Adminhtml\Vendor
 */
abstract class AbstractMassAction extends Action
{
    /**
     * @var string
     */
    protected $redirectUrl = '*/*/index';
    /**
     * @var Filter
     */
    protected $filter;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * AbstractMassAction constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            return $this->massAction($collection);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath($this->redirectUrl);
        }
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_Multivendor::vendor');
    }

    /**
     * @return string
     */
    protected function getComponentRefererUrl()
    {
        return '*/*/index';
    }

    /**
     * @param Collection $collection
     * @return mixed
     */
    abstract protected function massAction(Collection $collection);
}
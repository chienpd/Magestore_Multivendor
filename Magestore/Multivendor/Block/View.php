<?php

namespace Magestore\Multivendor\Block;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magestore\Multivendor\Helper\Config;

/**
 * Class View
 * @package Magestore\Multivendor\Block
 */
class View extends Template
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var Config
     */
    protected $_configHelper;
    /**
     *
     */
    const STATUS_ENABLED = 1;


    /**
     * View constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param Config $configHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        Config $configHelper,
        array $data
    )
    {
        $this->_objectManager = $objectManager;
        $this->_storeManager = $context->getStoreManager();
        $this->_configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    /**
     * @param string $imagePath
     * @return string
     */
    public function getMediaUrlImage($imagePath = '')
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) .
            $imagePath;
    }

    /**
     * @return mixed
     */
    public function getVendorModel()
    {
        $id = $this->getRequest()->getParam('id');
        $vendorModel = $this->_objectManager->create('Magestore\Multivendor\Model\Vendor')
            ->load($id);
        return $vendorModel;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        if ($this->_configHelper->getStoreConfig('multivendor/general/active') == 0) {
            return '';
        } else {
            return parent::toHtml();
        }
    }
}
<?php

namespace Magestore\Multivendor\Plugin\Catalog;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManager;

/**
 * Class Layer
 * @package Magestore\Multivendor\Plugin\Catalog
 */
class Layer
{
    /**
     * @var RequestInterface
     */
    protected $_request;
    /**
     * @var StoreManager
     */
    protected $_storeManager;
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Layer constructor.
     * @param RequestInterface $request
     * @param StoreManager $storeManager
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        RequestInterface $request,
        StoreManager $storeManager,
        ObjectManagerInterface $objectManager
    )
    {
        $this->_request = $request;
        $this->_storeManager = $storeManager;
        $this->_objectManager = $objectManager;
    }

    /**
     * @param \Magento\Catalog\Model\Layer $object
     * @param $listProduct
     * @return mixed
     */
    public function afterGetProductCollection(\Magento\Catalog\Model\Layer $object,
                                              $listProduct)
    {
        if ($this->_request->getRouteName() == 'multivendor') {
            $id = $this->_request->getParam('id');
            $vendorProductModel = $this->_objectManager->create('Magestore\Multivendor\Model\VendorProduct')->load($id, 'vendor_id');
            $productIds = $vendorProductModel->getProductIds();
            $productIdArray = explode(',', $productIds);
            $listProduct->getSelect()->where('e.entity_id in (?)', $productIdArray);
        }
        return $listProduct;
    }
}
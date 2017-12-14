<?php

namespace Magestore\Multivendor\Block\Adminhtml\Vendor\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Framework\Module\Manager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\Store;


/**
 * Class Product
 * @package Magestore\Multivendor\Block\Adminhtml\Vendor\Edit\Tab
 */
class Product extends Extended implements TabInterface
{

    /**
     * @var
     */
    protected $_systemStore;
    /**
     * @var
     */
    protected $_resource;
    /**
     * @var ProductFactory
     */
    protected $_productFactory;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;
    /**
     * @var CollectionFactory
     */
    protected $_setsFactory;
    /**
     * @var Status
     */
    protected $_status;
    /**
     * @var Visibility
     */
    protected $_visibility;
    /**
     * @var Type
     */
    protected $_type;
    /**
     * @var Manager
     */
    protected $moduleManager;
    /**
     * @var Registry
     */
    protected $_coreRegistry;
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param ProductFactory $productFactory
     * @param Type $type
     * @param \Magento\Catalog\Model\Product $product
     * @param CollectionFactory $setsFactory
     * @param Status $status
     * @param Visibility $visibility
     * @param Manager $moduleManager
     * @param Registry $coreRegistry
     * @param ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        ProductFactory $productFactory,
        Type $type,
        \Magento\Catalog\Model\Product $product,
        CollectionFactory $setsFactory,
        Status $status,
        Visibility $visibility,
        Manager $moduleManager,
        Registry $coreRegistry,
        ObjectManagerInterface $objectManager,
        array $data = array()
    )
    {
        $this->_objectManager = $objectManager;
        $this->_productFactory = $productFactory;
        $this->_product = $product;
        $this->_type = $type;
        $this->_setsFactory = $setsFactory;
        $this->_status = $status;
        $this->_visibility = $visibility;
        $this->moduleManager = $moduleManager;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'attribute_set_id'
        )->addAttributeToSelect(
            'type_id'
        )->setStore(
            $store
        );

        if ($this->moduleManager->isEnabled('Magento_CatalogInventory')) {
            $collection->joinField(
                'qty',
                'cataloginventory_stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left'
            );
        }
        if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                Store::DEFAULT_STORE_ID
            );
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
        } else {
            $collection->addAttributeToSelect('price');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        }

        $this->setCollection($collection);

        parent::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
    }

    /**
     * Retrieve selected related products
     *
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getProductsVendor();
        if (!is_array($products)) {
            $products = $this->getSelectedVendorProducts();
        }
        return $products;
    }

    /**
     * Retrieve related products
     *
     * @return array
     */
    public function getSelectedVendorProducts()
    {
        $products = [];
        $currentVendorModel = $this->_objectManager->create('Magestore\Multivendor\Model\Vendor')
            ->load($this->getRequest()->getParam('id'));
        if ($currentVendorModel->getId()) {
            $vendorId = $currentVendorModel->getId();
            $vendorProductModel = $this->_objectManager->create('Magestore\Multivendor\Model\VendorProduct')
                ->load($vendorId, 'vendor_id');
            $productIds = $vendorProductModel->getProductIds();
            $products = explode(',', $productIds);
        }

        return $products;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'in_products',
            [
                'type' => 'checkbox',
                'name' => 'in_products',
                'values' => $this->_getSelectedProducts(),
                'align' => 'center',
                'index' => 'entity_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select'
            ]
        );

        $this->addColumn(
            'entity_id_product',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'product_name',
            [
                'header' => __('Name'),
                'index' => 'name',
            ]
        );

        $this->addColumn(
            'type',
            [
                'header' => __('Type'),
                'index' => 'type_id',
                'type' => 'options',
                'options' => $this->_type->getOptionArray()
            ]
        );

        $sets = $this->_setsFactory->create()->setEntityTypeFilter(
            $this->_productFactory->create()->getResource()->getTypeId()
        )->load()->toOptionHash();

        $this->addColumn(
            'set_name',
            [
                'header' => __('Attribute Set'),
                'index' => 'attribute_set_id',
                'type' => 'options',
                'options' => $sets,
                'header_css_class' => 'col-attr-name',
                'column_css_class' => 'col-attr-name'
            ]
        );

        $this->addColumn(
            'sku',
            [
                'header' => __('SKU'),
                'index' => 'sku'
            ]
        );

        $store = $this->_getStore();
        $this->addColumn(
            'product_price',
            [
                'header' => __('Price'),
                'type' => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
                'index' => 'price',
                'header_css_class' => 'col-price',
                'column_css_class' => 'col-price'
            ]
        );
        if ($this->moduleManager->isEnabled('Magento_CatalogInventory')) {
            $this->addColumn(
                'qty',
                [
                    'header' => __('Quantity'),
                    'type' => 'number',
                    'index' => 'qty'
                ]
            );
        }

        $this->addColumn(
            'visibility',
            [
                'header' => __('Visibility'),
                'index' => 'visibility',
                'type' => 'options',
                'options' => $this->_visibility->getOptionArray(),
                'header_css_class' => 'col-visibility',
                'column_css_class' => 'col-visibility'
            ]
        );


        $this->addColumn(
            'product_status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => $this->_status->getOptionArray()
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'catalog/product/edit',
                            'params' => ['store' => $this->getRequest()->getParam('store')]
                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        return parent::_prepareColumns();
    }


    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('View Product');
    }


    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('View Product');
    }


    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }


    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productgrid', ['id' => $this->getRequest()->getParam('id')]);
    }


}

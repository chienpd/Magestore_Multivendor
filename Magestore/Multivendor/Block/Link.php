<?php

namespace Magestore\Multivendor\Block;

use Magento\Framework\View\Element\Template\Context;
use Magestore\Multivendor\Helper\Config;

/**
 * Class Link
 * @package Magestore\Multivendor\Block
 */
class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * @var Config
     */
    protected $_configHelper;

    /**
     * Link constructor.
     * @param Context $context
     * @param Config $configHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $configHelper,
        array $data = []
    )
    {
        $this->_configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->_storeManager->getStore()->getUrl('multivendor/vendor/listing');
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        if ($this->_configHelper->getStoreConfig('multivendor/general/enable_toplink') == 0) {
            return '';
        } else {
            return parent::toHtml();
        }
    }
}
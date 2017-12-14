<?php

namespace Magestore\Multivendor\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package Magestore\Multivendor\Helper
 */
class Config extends AbstractHelper
{
    /**
     * @param $path
     * @return mixed
     */
    public function getStoreConfig($path)
    {
        return $this->scopeConfig->getValue($path,
            ScopeInterface::SCOPE_STORE);
    }
}
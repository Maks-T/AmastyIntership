<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

abstract class ConfigProviderAbstract
{
    /**
     * @var string
     */
    protected $pathPrefix = 'tsatsura_config/';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(ScopeConfigInterface $scopeConfig, StoreManagerInterface $storeManager)
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    public function getValue($path, $storeId = null, $scope = 'store'): mixed
    {
        if (!$storeId) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        return $this->scopeConfig->getValue($this->pathPrefix . $path, $scope, $storeId);
    }
}
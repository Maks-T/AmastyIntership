<?php

declare(strict_types=1);

namespace Amasty\SecondTsatsuraModule\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

abstract class ConfigProviderAbstract
{
    /**
     * @var string
     */
    protected $pathPrefix = 'second_tsatsura_config/';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getValue( string $path,  string $storeId = null, string $scope = 'store'): string
    {
        return $this->scopeConfig->getValue($this->pathPrefix . $path, $scope, $storeId);
    }
}
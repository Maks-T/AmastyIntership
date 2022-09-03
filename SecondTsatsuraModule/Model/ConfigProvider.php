<?php

declare(strict_types=1);

namespace Amasty\SecondTsatsuraModule\Model;

class ConfigProvider extends ConfigProviderAbstract
{
    public const ENABLED_PARAM = 'general/enabled';
    public const PROMO_SKU = 'promo_product/promo_sku';
    public const FOR_SKU = 'promo_product/for_sku';

    public function isModuleEnabled(): bool
    {
        return (bool)$this->getValue(self::ENABLED_PARAM);
    }

    public function getPromoSku(): string
    {
        return (string)$this->getValue(self::PROMO_SKU);
    }

    public function getForSku(): array
    {
        return array_unique(explode(',', $this->getValue(self::FOR_SKU)));
    }
}
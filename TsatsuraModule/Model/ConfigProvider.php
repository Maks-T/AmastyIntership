<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Model;

class ConfigProvider extends ConfigProviderAbstract
{
    const ENABLED_PARAM = 'general/enabled';
    const QTY_PARAM = 'general/enabled_qty';
    const QTY_VALUE_PARAM = 'general/qty_value';
    const WELCOME_TEXT_PARAM = 'general/welcome_text';

    public function isModuleEnabled(): bool
    {
        return (bool)$this->getValue(self::ENABLED_PARAM);
    }

    public function isEnabledQty(): bool
    {
        return (bool)$this->getValue(self::QTY_PARAM);
    }

    public function getValueQty(): int
    {
        return (int)$this->getValue(self::QTY_VALUE_PARAM);
    }

    public function getWelcomeText(): string
    {
        return (string)$this->getValue(self::WELCOME_TEXT_PARAM);
    }
}
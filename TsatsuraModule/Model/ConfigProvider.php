<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Model;

class ConfigProvider extends ConfigProviderAbstract
{
    public const ENABLED_PARAM = 'general/enabled';
    public const QTY_PARAM = 'general/enabled_qty';
    public const QTY_VALUE_PARAM = 'general/qty_value';
    public const WELCOME_TEXT_PARAM = 'general/welcome_text';
    const EMAIL = 'general/email';
    const EMAIL_TEMPLATE = 'general/email_template';

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

    public function getEmail(): string
    {
        return (string)$this->getValue(self::EMAIL);
    }

    public function getEmailTemplate()
    {
        return (string)$this->getValue(self::EMAIL_TEMPLATE);
    }
}
<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;

class Form extends Template
{
    const QTY_PARAM = 'tsatsura_config/general/enabled_qty';

    const QTY_VALUE_PARAM = 'tsatsura_config/general/qty_value';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabledQty(): bool
    {
        return $this->scopeConfig->isSetFlag(self::QTY_PARAM);
    }

    public function getValueQty(): int
    {
        return (int)$this->scopeConfig->getValue(self::QTY_VALUE_PARAM);
    }
}
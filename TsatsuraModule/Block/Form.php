<?php
declare(strict_types=1);
namespace Amasty\TsatsuraModule\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;

class Form extends Template
{
    private ScopeConfigInterface $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->scopeConfig = $scopeConfig;
    }

    public function isDisable():string
    {
        return $this->scopeConfig->isSetFlag('tsatsura_config/general/enabled_qty')?'':' disable';
    }

    public function getValueQty():int|string
    {
        $valueQty = $this->scopeConfig->getValue('tsatsura_config/general/qty_value');
        return $valueQty ? (int)$valueQty : '';
    }
}
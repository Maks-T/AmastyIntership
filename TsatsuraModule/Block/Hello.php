<?php
declare(strict_types=1);
namespace Amasty\TsatsuraModule\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;

class Hello extends Template
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

    public function getWelcomeText():string
    {
        return $this->scopeConfig->getValue('tsatsura_config/general/welcome_text') ?:'something went wrong';
    }
}

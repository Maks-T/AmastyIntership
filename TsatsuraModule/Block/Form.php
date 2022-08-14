<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Block;

use Amasty\TsatsuraModule\Model\ConfigProvider;
use Magento\Framework\View\Element\Template;

class Form extends Template
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        Template\Context $context,
        ConfigProvider $configProvider,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->configProvider = $configProvider;
    }

    public function isEnabledQty(): bool
    {
        return $this->configProvider->isEnabledQty();
    }

    public function getValueQty(): int
    {
        return $this->configProvider->getValueQty();
    }
}
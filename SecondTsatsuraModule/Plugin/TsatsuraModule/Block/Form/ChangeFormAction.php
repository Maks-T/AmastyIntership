<?php

declare(strict_types=1);

namespace Amasty\SecondTsatsuraModule\Plugin\TsatsuraModule\Block\Form;

use Amasty\TsatsuraModule\Block\Form;
use Magento\Framework\View\Element\Template;

class ChangeFormAction extends  Template
{
    public const FORM_ACTION = 'checkout/cart/add';

    public function afterGetFormAction(Form $subject, string $result): string
    {
        return $this->getUrl(self::FORM_ACTION, ['_secure' => true]);
    }
}
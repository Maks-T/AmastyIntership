<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Model;

use Amasty\TsatsuraModule\Model\ResourceModel\Blacklist as BlacklistResource;
use Magento\Framework\Model\AbstractModel;

class Blacklist extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(
            BlacklistResource::class
        );
    }
}
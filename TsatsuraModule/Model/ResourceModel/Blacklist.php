<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Blacklist extends AbstractDb
{
    public const TABLE_NAME = 'amasty_tsatsuramodule_blacklist';

    public const ID_FIELD_NAME = 'entity_id';

    protected function _construct()
    {
        $this->_init(
            self::TABLE_NAME,
            self::ID_FIELD_NAME
        );
    }
}
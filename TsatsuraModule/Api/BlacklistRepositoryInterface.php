<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Api;

use Amasty\TsatsuraModule\Model\Blacklist;

interface BlacklistRepositoryInterface
{
    public function getBySku($productSku): Blacklist;

    public function deleteBySku($productSku): void;

    public function setProductQty($productSku, $productQty): void;
}
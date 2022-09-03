<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Api;

use Amasty\TsatsuraModule\Model\Blacklist;

interface BlacklistRepositoryInterface
{
    public function getBySku(string $productSku): Blacklist;

    public function getById(int $id): Blacklist;

    public function getVarsById(int $id): array;

    public function deleteBySku(string $productSku): void;

    public function setProductQty(string $productSku, string $productQty): void;

    public function setEmailBodyById(int $id, string $emailBody): void;
}
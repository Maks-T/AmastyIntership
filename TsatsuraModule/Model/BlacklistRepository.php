<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Model;

use Amasty\TsatsuraModule\Api\BlacklistRepositoryInterface;
use Amasty\TsatsuraModule\Model\ResourceModel\Blacklist as BlacklistResource;

class BlacklistRepository implements BlacklistRepositoryInterface
{
    public const ID_FIELD_NAME_PRODUCT_SKU = 'product_sku';

    /**
     * @var BlacklistFactory
     */
    private $blacklistFactory;

    /**
     * @var BlacklistResource
     */
    private $blacklistResource;

    public function __construct(
        BlacklistFactory $blacklistFactory,
        BlacklistResource $blacklistResource
    ) {
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistResource = $blacklistResource;
    }

    public function getBySku(string $productSku): Blacklist
    {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load(
            $blacklist,
            $productSku,
            self::ID_FIELD_NAME_PRODUCT_SKU
        );

        return $blacklist;
    }

    public function getById(int $id): Blacklist {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load($blacklist, $id);

        return $blacklist;
    }

    public function getVarsById(int $id): array {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load($blacklist, $id);

        return [
            'entity_id'=>$blacklist->getId(),
            'product_sku'=>$blacklist->getProductSku(),
            'product_qty'=>$blacklist->getProductQty(),
        ];;
    }

    public function deleteBySku(string $productSku): void
    {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load(
            $blacklist,
            $productSku,
            self::ID_FIELD_NAME_PRODUCT_SKU
        );

        $this->blacklistResource->delete($productSku);
    }

    public function setProductQty(string $productSku, string $productQty): void
    {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load(
            $blacklist,
            $productSku,
            self::ID_FIELD_NAME_PRODUCT_SKU
        );
        $blacklist->setProductQty($productQty);
        $this->blacklistResource->save($blacklist);
    }

    public function setEmailBodyById(int $id, string $emailBody): void
    {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load($blacklist, $id);
        $blacklist->setEmailBody($emailBody);
        $this->blacklistResource->save($blacklist);
    }
}
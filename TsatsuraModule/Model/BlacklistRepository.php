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

    public function getBySku($productSku): Blacklist
    {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load(
            $blacklist,
            $productSku,
            self::ID_FIELD_NAME_PRODUCT_SKU
        );

        return $blacklist;
    }

    public function deleteBySku($productSku): void
    {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load(
            $blacklist,
            $productSku,
            self::ID_FIELD_NAME_PRODUCT_SKU
        );

        $this->blacklistResource->delete($productSku);
    }

    public function setProductQty($productSku, $productQty): void
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
}
<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Model;

use Amasty\TsatsuraModule\Api\BlacklistRepositoryInterface;
use Amasty\TsatsuraModule\Model\BlacklistFactory;
use Amasty\TsatsuraModule\Model\ResourceModel\Blacklist as BlacklistResource;

class BlacklistRepository implements BlacklistRepositoryInterface
{
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
            'product_sku'
        );

        return $blacklist;
    }

    public function setProductQty($productSku, $productQty): void
    {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load(
            $blacklist,
            $productSku,
            'product_sku'
        );
        $blacklist->setProductQty($productQty);
        $this->blacklistResource->save($blacklist);
    }
}
<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Block;

use Amasty\TsatsuraModule\Model\BlacklistRepository;
use Amasty\TsatsuraModule\Model\ResourceModel\Blacklist\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Element\Template;

class EmailBlacklist extends Template
{
    /**
     * @var CollectionFactory
     */
    private $blacklistCollectionFactory;

    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $blacklistCollectionFactory,
        BlacklistRepository $blacklistRepository,
        ResultFactory $resultFactory,
        array $data=[]
    ) {
        $this->blacklistRepository = $blacklistRepository;
        $this->resultFactory = $resultFactory;
        $this->blacklistCollectionFactory = $blacklistCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getBlacklistCollection(): CollectionFactory
    {
        return $this->blacklistCollectionFactory->create();
    }
}
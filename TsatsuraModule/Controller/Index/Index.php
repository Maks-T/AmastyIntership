<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Controller\Index;

use Amasty\TsatsuraModule\Model\ConfigProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultFactory;

class Index implements ActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var ForwardFactory
     */
    private $forwardFactory;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ResultFactory $resultFactory,
        ForwardFactory $forwardFactory,
        ConfigProvider $configProvider,
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $collectionFactory
    ) {
       $this->resultFactory = $resultFactory;
       $this->forwardFactory = $forwardFactory;
       $this->configProvider = $configProvider;
       $this->checkoutSession = $checkoutSession;
       $this->productRepository = $productRepository;
       $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
       if ($this->configProvider->isModuleEnabled()) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
           $resultForward = $this->forwardFactory->create();
           $resultForward->setController('index');
           $resultForward->forward('defaultNoRoute');
           return $resultForward;
        }

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
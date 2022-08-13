<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultFactory;

class Index implements ActionInterface
{
    const ENABLED_PARAM = 'tsatsura_config/general/enabled';
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var ForwardFactory
     */
    private $forwardFactory;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

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
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $collectionFactory
    ) {
       $this->resultFactory = $resultFactory;
       $this->forwardFactory = $forwardFactory;
       $this->scopeConfig = $scopeConfig;
       $this->checkoutSession = $checkoutSession;
       $this->productRepository = $productRepository;
       $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {

       if ($this->scopeConfig->isSetFlag(self::ENABLED_PARAM)) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
           $resultForward = $this->forwardFactory->create();
           $resultForward->setController('index');
           $resultForward->forward('defaultNoRoute');
           return $resultForward;
        }

       /* $quote = $this->checkoutSession->getQuote();

        if (!$quote->getId()) {
            $quote->save();
        }*/

        //$product = $this->productRepository->get('24-MB01');

       /* $productCollection = $this->collectionFactory->create();
        $productCollection->addAttributeToFilter('sku', ['like' => '24-MB%']);
        $productCollection->addAttributeToSelect('sku');

        foreach ($productCollection->getItems() as $item) {
            echo $item->getSku();
            echo '<br>';
        }*/

        /*$quote->addProduct($product, 1);
        $quote->save();*/
        /*die('done');*/
        //return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
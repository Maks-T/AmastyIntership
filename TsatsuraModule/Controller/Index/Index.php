<?php
declare(strict_types=1);
namespace Amasty\TsatsuraModule\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;

class Index implements ActionInterface
{
    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    private ScopeConfigInterface $scopeConfig;

    private Session $checkoutSession;

    private ProductRepositoryInterface $productRepository;

    private CollectionFactory $collectionFactory;

    public function __construct(
        ResultFactory $resultFactory,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $collectionFactory
    ) {
       $this->resultFactory = $resultFactory;
       $this->scopeConfig = $scopeConfig;
       $this->checkoutSession = $checkoutSession;
       $this->productRepository = $productRepository;
       $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
       if ($this->scopeConfig->isSetFlag('tsatsura_config/general/enabled')) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
            die('The Tsatsura module is currently disabled in the admin settings.
Please go to module settings and enable it.');
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
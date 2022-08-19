<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Controller\Index;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;

class Search implements ActionInterface
{
    public const SKU_PARAM = 'sku';

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ResultFactory $resultFactory,
        RequestInterface $request,
        CollectionFactory $collectionFactory
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $searchRequest = $this->request->getParam(self::SKU_PARAM);
        $products = $this->getProductsBySku($searchRequest);
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($products);

        return $resultJson;
    }

    public function getProductsBySku($searchRequest)
    {
        $collectionFactory = $this->collectionFactory->create();
        $collectionFactory->addAttributeToFilter('sku', ['like' => "%{$searchRequest}%"]);
        $collectionFactory->addAttributeToSelect('*');
        $collectionFactory->setPageSize(10);
        $products = [];

        foreach ($collectionFactory as $product) {
            $products[] = [
                'sku' => $product->getDataByKey(self::SKU_PARAM),
                'name' => $product->getName()
            ];
        }

        return $products;
    }
}
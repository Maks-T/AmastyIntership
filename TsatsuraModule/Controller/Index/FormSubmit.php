<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\QuoteRepository;
use Amasty\TsatsuraModule\Model\BlacklistRepository;
use Magento\Quote\Model\Quote;


class FormSubmit implements ActionInterface
{
    public const SKU_PARAM = 'sku';
    public const QTY_PARAM = 'qty';
    public const PRODUCT_ADDED_EVENT = 'amasty_add_product_to_cart';
    public const PRODUCT_ADDED_SKU = 'product_added_sku';

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    public function __construct(
        ResultFactory $resultFactory,
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        RequestInterface $request,
        RedirectInterface $redirect,
        ManagerInterface $messageManager,
        QuoteRepository $quoteRepository,
        EventManager $eventManager,
        BlacklistRepository $blacklistRepository
    ) {
        $this->resultFactory = $resultFactory;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->messageManager = $messageManager;
        $this->quoteRepository = $quoteRepository;
        $this->eventManager = $eventManager;
        $this->blacklistRepository = $blacklistRepository;
    }

    public function execute(): ResultInterface
    {
        $quote = $this->checkoutSession->getQuote();

         if (!$quote->getId()) {
             $quote->save();
         }

        try {
            $productSkuValue = $this->request->getParam(self::SKU_PARAM);
            $productQtyValue = $this->request->getParam(self::QTY_PARAM);
            $product = $this->productRepository->get($productSkuValue);

            if ($this->isProductSimple($product, $productSkuValue)) {
                return $this->resultRedirect();
            }

            $blacklistProduct = $this->blacklistRepository->getBySku($product->getSku());

            if (empty($blacklistProduct)) {
                $this->addProductToQuote($quote, $product, $productSkuValue, $productQtyValue);
            } else {
                echo 'product in blacklist';
            }


        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->resultRedirect();
    }

    private function addProductToQuote(Quote $quote, Product $product, string $productSkuValue, string $productQtyValue) {
        $quote->addProduct($product, $productQtyValue);
        $this->quoteRepository->save($quote);
        $this->messageManager->addSuccessMessage
        (
            __('Product with SKU=%1 in the amount of %2 pieces added to cart', $productSkuValue, $productQtyValue)
        );

        $this->eventManager->dispatch(
            self::PRODUCT_ADDED_EVENT,
            [self::PRODUCT_ADDED_SKU => $productSkuValue]
        );
    }

    private function isProductSimple(Product $product, string $productSkuValue): bool
    {
        if ($product->getTypeId() !== Type::TYPE_SIMPLE) {
            $this->messageManager->addWarningMessage
            (
                __('Product with SKU=%1 is a %2 type. Only simple product is available', $productSkuValue, $product->getTypeId())
            );
            return true;
        }
        return false;
    }

    private function resultRedirect(): ResultInterface
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->redirect->getRefererUrl());

        return $resultRedirect;
    }
}
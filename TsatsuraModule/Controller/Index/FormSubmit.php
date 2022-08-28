<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Controller\Index;

use Amasty\TsatsuraModule\Model\Blacklist;
use Amasty\TsatsuraModule\Model\BlacklistRepository;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;

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

            $this->checkProductAndAddToQuote(
                $quote,
                $product,
                $productSkuValue,
                $productQtyValue
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->resultRedirect();
    }

    /**
     * @throws LocalizedException
     */
    private function checkProductAndAddToQuote(
        Quote $quote,
        Product $product,
        string $productSkuValue,
        string $productQtyValue
    ): void {
        $blacklistProduct = $this->blacklistRepository->getBySku($product->getSku());

        if ($blacklistProduct->isEmpty()) {
            $this->addProductToQuote(
                $quote,
                $product,
                $productSkuValue,
                $productQtyValue);
        } else {
            $this->addProductFromBlackList(
                $quote,
                $product,
                $productSkuValue,
                $productQtyValue,
                $blacklistProduct
            );
        }
    }

    /**
     * @throws LocalizedException
     */
    private function addProductToQuote(
        Quote $quote,
        Product $product,
        string $productSkuValue,
        string $productQtyValue
    ): void {
        $quote->addProduct($product, $productQtyValue);
        $this->quoteRepository->save($quote);
        $this->messageManager->addSuccessMessage
        (
            __(
                'Product with SKU=%1 in the amount of %2 pieces added to cart',
                $productSkuValue,
                $productQtyValue
            )
        );

        $this->eventManager->dispatch(
            self::PRODUCT_ADDED_EVENT,
            [self::PRODUCT_ADDED_SKU => $productSkuValue]
        );
    }

    /**
     * @throws LocalizedException
     */
    private function addProductFromBlackList(
        Quote $quote,
        Product $product,
        string $productSkuValue,
        string $productQtyValue,
        Blacklist $blacklistProduct
    ): void {
        $qtyProductInQuote = $this->getQtyInQuote($quote, $product);
        $qtyProductInBlacklist = $blacklistProduct->getProductQty();

        if ($qtyProductInQuote + $productQtyValue > $qtyProductInBlacklist) {
            $allowedQty = $qtyProductInBlacklist - $qtyProductInQuote;
            if ($allowedQty > 0) {
                $this->messageManager->addErrorMessage(
                    __(
                        'The product with SKU=%1 is in the blacklist in the amount of %2 pieces. ' .
                        'Currently there are %3 pieces in the cart of this product. ' .
                        'Only %4 products were added to the cart',
                        $productSkuValue, $qtyProductInBlacklist, $qtyProductInQuote, $allowedQty
                    )
                );
                $this->addProductToQuote($quote, $product, $productSkuValue, (string)$allowedQty);
            } else {
                $this->messageManager->addErrorMessage(
                    __(
                        'The product with SKU=%1 is in the blacklist in the amount of %2 pieces. ' .
                        'Currently there are %3 pieces in the cart of this product. ' .
                        'The product has not been added',
                        $productSkuValue, $qtyProductInBlacklist, $qtyProductInQuote
                    )
                );
            }
        } else {
            {
                $this->addProductToQuote(
                    $quote,
                    $product,
                    $productSkuValue,
                    $productQtyValue
                );
            }
        }
    }

    private function getQtyInQuote(Quote $quote, Product $product): int
    {
        $quoteItems = $quote->getItems();
        $qtyInQuote = 0;

        foreach ($quoteItems as $item) {
            if ($item->getSku() === $product->getSku()) {
                $qtyInQuote = $item->getQty();
                break;
            }
        }

        return (int)$qtyInQuote;
    }

    private function isProductSimple(Product $product, string $productSkuValue): bool
    {
        if ($product->getTypeId() !== Type::TYPE_SIMPLE) {
            $this->messageManager->addWarningMessage
            (
                __(
                    'Product with SKU=%1 is a %2 type. Only simple product is available',
                    $productSkuValue,
                    $product->getTypeId()
                )
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
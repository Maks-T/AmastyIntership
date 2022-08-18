<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\QuoteRepository;

class FormSubmit implements ActionInterface
{
    public const SKU_PARAM = 'sku';
    public const QTY_PARAM = 'qty';

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

    public function __construct(
        ResultFactory $resultFactory,
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        RequestInterface $request,
        RedirectInterface $redirect,
        ManagerInterface $messageManager,
        QuoteRepository $quoteRepository
    ) {
        $this->resultFactory = $resultFactory;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->messageManager = $messageManager;
        $this->quoteRepository = $quoteRepository;
    }

    public function execute()
    {
        $quote = $this->checkoutSession->getQuote();

         if (!$quote->getId()) {
             $quote->save();
         }

        try {
            $productSkuValue = $this->request->getParam(self::SKU_PARAM);
            $productQtyValue = $this->request->getParam(self::QTY_PARAM);
            $product = $this->productRepository->get($productSkuValue);

            if ($product->getTypeId() !== Type::TYPE_SIMPLE) {
                $this->messageManager->addWarningMessage
                (
                    __('Product with SKU=%1 is a %2 type. Only simple product is available', $productSkuValue, $product->getTypeId())
                );

                return $this->resultRedirect();
            }
            $quote->addProduct($product, $productQtyValue);
            $this->quoteRepository->save($quote);
            $this->messageManager->addSuccessMessage
            (
                __('Product with SKU=%1 in the amount of %2 pieces added to cart', $productSkuValue, $productQtyValue)
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->resultRedirect();
    }

    private function resultRedirect()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->redirect->getRefererUrl());

        return $resultRedirect;
    }
}
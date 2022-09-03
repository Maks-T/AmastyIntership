<?php

declare(strict_types=1);

namespace Amasty\SecondTsatsuraModule\Plugin\Checkout\Controller\Cart\Add;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Checkout\Controller\Cart\Add;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class ChangeProductData
{
    public const SKU_PARAM = 'sku';

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var ProductRepositoryInterface
     */
    public $productRepository;

    /**
     * @var ManagerInterface
     */
    public $messageManager;

    public function __construct(
        ResultFactory $resultFactory,
        RedirectInterface $redirect,
        ProductRepositoryInterface $productRepository,
        ManagerInterface $messageManager,
    ) {
        $this->resultFactory = $resultFactory;
        $this->redirect = $redirect;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    public function beforeExecute(Add $subject): ResultInterface | Add
    {
        try {
            $product = $this->productRepository->get(
                $subject->getRequest()->getParam(self::SKU_PARAM)
            );

            if ($product->getTypeId() !== Type::TYPE_SIMPLE) {
                $this->messageManager->addWarningMessage
                (
                    __('Product with SKU=%1 is not Simple type. Only simple product is available', $product->getTypeId())
                );

                return $this->resultRedirect();
            }

            $subject->getRequest()->setParams(['product' => $product->getId()]);

            return $subject;
        } catch (NoSuchEntityException $e) {
            return $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    private function resultRedirect(): ResultInterface
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->redirect->getRefererUrl());

        return $resultRedirect;
    }
}
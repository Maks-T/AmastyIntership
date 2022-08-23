<?php

namespace Amasty\SecondTsatsuraModule\Observer;

use Amasty\SecondTsatsuraModule\Model\ConfigProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use NoSuchEntityException;

class AddPromoSku implements ObserverInterface
{
    public const PRODUCT_ADDED_SKU = 'product_added_sku';

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
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        ConfigProvider $configProvider,
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        ManagerInterface $messageManager
    ) {
        $this->configProvider = $configProvider;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer): void
    {
        if ($this->configProvider->isModuleEnabled()) {
            $promoSku = $this->configProvider->getPromoSku();
            $forSku = $this->configProvider->getForSku();

            try {
                $promoProduct = $this->productRepository->get($promoSku);
            } catch (NoSuchEntityException $e) {
                $promoProduct = null;
            }

            if ($promoProduct) {
                $addedProductSku = $observer->getData(self::PRODUCT_ADDED_SKU);
                foreach ($forSku as $sku) {
                    if ($sku === $addedProductSku) {
                        $quote = $this->checkoutSession->getQuote();
                        $quote->addProduct($promoProduct, 1);
                        $quote->save();
                        $this->messageManager->addSuccessMessage('Promo product is added');
                    }
                }
            }
        }
    }
}
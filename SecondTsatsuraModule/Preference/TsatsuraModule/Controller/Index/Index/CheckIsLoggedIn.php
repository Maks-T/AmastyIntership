<?php

declare(strict_types=1);

namespace Amasty\SecondTsatsuraModule\Preference\TsatsuraModule\Controller\Index\Index;

use Amasty\TsatsuraModule\Model\ConfigProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Checkout\Model\Session;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultFactory;
use Amasty\TsatsuraModule\Controller\Index\Index;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

class CheckIsLoggedIn extends Index
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

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        ResultFactory $resultFactory,
        ForwardFactory $forwardFactory,
        ConfigProvider $configProvider,
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $collectionFactory,
        CustomerSession $customerSession,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager
    ) {
        parent::__construct($resultFactory,$forwardFactory,$configProvider,$checkoutSession,$productRepository,$collectionFactory);
        $this->customerSession = $customerSession;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        if($this->customerSession->isLoggedIn()) {

            return parent::execute();
        } else {
            $this->messageManager->addErrorMessage(__('You must confirm your account.'));

            return $this->redirectFactory->create()->setPath('customer/account/create');
        }
    }
}
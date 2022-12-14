<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Plugin\SecondTsatsuraModule\Observer\AddPromoSku;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Amasty\SecondTsatsuraModule\Observer\AddPromoSku;
use Magento\Framework\Event\Observer;

class CheckIsAjaxRequest
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        RequestInterface $request,
        ManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->messageManager = $messageManager;
    }

    public function aroundExecute(AddPromoSku $subject, callable $proceed, Observer $observer)
    {
        if (!$this->request->isAjax()) {
            return $proceed($observer);
        } else {
            $this->messageManager->addWarningMessage('Ajax request sent! Promo product isn\'t added');
        }
    }
}
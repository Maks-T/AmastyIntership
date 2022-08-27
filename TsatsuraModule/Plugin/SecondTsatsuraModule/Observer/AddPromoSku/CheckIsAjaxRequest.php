<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Plugin\SecondTsatsuraModule\Observer\AddPromoSku;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;

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

    public function aroundExecute($subject, callable $proceed, $observer)
    {
        if (!$this->request->isAjax()) {
            return $proceed($observer);
        } else {
            $this->messageManager->addWarningMessage('Ajax request sent! Promo product isn\'t added');
        }
    }
}
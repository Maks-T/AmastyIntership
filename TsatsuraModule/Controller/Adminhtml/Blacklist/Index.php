<?php

namespace Amasty\TsatsuraModule\Controller\Adminhtml\Blacklist;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index implements ActionInterface
{
     /**
     * @var ResultFactory
     */
    private $resultFactory;

    public function __construct(
        ResultFactory $resultFactory,
    ) {
        $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Amasty_TsatsuraModule::blacklist');
        $resultPage->getConfig()->getTitle()->prepend(__('Blacklist'));

        return $resultPage;
    }
}
<?php
declare(strict_types=1);
namespace Amasty\TsatsuraModule\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index implements ActionInterface
{
    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    public function __construct(ResultFactory $resultFactory) {
       $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
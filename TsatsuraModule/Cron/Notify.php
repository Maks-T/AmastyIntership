<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Cron;

use Amasty\TsatsuraModule\Model\BlacklistRepository;
use Amasty\TsatsuraModule\Model\ConfigProvider;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;

class Notify
{
    public const NAME_SENDER= 'Tsatsura';
    public const DEFAULT_EMAIL = 'maxim.tsatsura@gmail.com';
    public const ID_ITEM_BLACKLIST = 1;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    /**
     * @var FactoryInterface
     */
    private $templateFactory;

    public function __construct(
        ConfigProvider $configProvider,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        BlacklistRepository $blacklistRepository,
        FactoryInterface $templateFactory,
    ){
        $this->configProvider = $configProvider;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->blacklistRepository = $blacklistRepository;
        $this->templateFactory = $templateFactory;
    }

    public function execute(): void
    {
        $vars = $this->blacklistRepository->getVarsById(self::ID_ITEM_BLACKLIST);

        $sender = [
            'name'=>self::NAME_SENDER,
            'email'=>self::DEFAULT_EMAIL,
        ];

        $this->transportBuilder->setTemplateIdentifier(
            $this->configProvider->getEmailTemplate()
        )->setTemplateOptions(
            [
                'area' => Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId(),
            ]
        )->setTemplateVars(
            $vars
        )->setFromByScope(
            $sender
        )->addTo($this->configProvider->getEmail());

        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();

        $template = $this->templateFactory->get($this->configProvider->getEmailTemplate());
        $template->setOptions([
            'area' => Area::AREA_FRONTEND,
            'store' => $this->storeManager->getStore()->getId(),
        ]);
        $emailBody = $template->processTemplate();

        $this->blacklistRepository->setEmailBodyById(self::ID_ITEM_BLACKLIST, $emailBody);
    }
}
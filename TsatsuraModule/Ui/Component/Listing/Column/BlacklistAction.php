<?php

declare(strict_types=1);

namespace Amasty\TsatsuraModule\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class BlacklistAction extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $indexField = $this->getData('config/indexField') ?: 'entity_id';

                if (isset($item[$indexField])) {
                    $viewUrlPath = $this->getData('config/viewUrlPath') ?: '#';
                    $urlEntityParamName = $this->getData('config/urlEntityParamName') ?: 'entity_id';

                    $item[$this->getData('name')] = [
                        'view' => [
                            'href' => $this->urlBuilder->getUrl($viewUrlPath, [$urlEntityParamName => $item[$indexField]]),
                            'label' => __('Edit'),
                            '__disableTmpl' => true
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
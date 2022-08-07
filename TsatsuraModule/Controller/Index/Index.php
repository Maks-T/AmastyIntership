<?php

namespace Amasty\TsatsuraModule\Controller\Index;


use Magento\Framework\App\ActionInterface;

class Index implements ActionInterface {
    public function execute()
    {
        $style = '"
        display: flex; 
        justify-content: center; 
        align-items: center; 
        margin-top: 20%; 
        text-shadow: 4px 2px 1px rgba(144, 150, 150, 0.47); 
        color: blue;
        font-family: cursive;
        text-align: center;
        font-size: 3rem;
        "';

        $message = "<h1 style={$style}>Привет Magento. Привет Amasty. Я готов тебя покорить!</h1>";

        die($message);
    }
}
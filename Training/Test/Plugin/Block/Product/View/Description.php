<?php

namespace Training\Test\Plugin\Block\Product\View;


class Description
{
    public function beforeToHtml(
        \Magento\Catalog\Block\Product\View\Description $subject
    ) {
        $subject->getProduct()->setDescription('Test description');
    }
}
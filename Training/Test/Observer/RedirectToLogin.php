<?php

namespace Training\Test\Observer;

use Magento\Framework\Event\ObserverInterface;

class RedirectToLogin implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $_redirect;
    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $_actionFlag;
    /**
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     */
    public function __construct(
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\ActionFlag $actionFlag
    ) {
        $this->_redirect = $redirect;
        $this->_actionFlag = $actionFlag;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $request = $observer->getEvent()->getData('request');
        if ($request->getModuleName() == 'catalog'
            && $request->getControllerName() == 'product'
            && $request->getActionName() == 'view'
        ) {
        // if ($request->getFullActionName() == 'catalog_product_view') { // altenative way
            $controller = $observer->getEvent()->getData('controller_action');
            $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
            $this->_redirect->redirect($controller->getResponse(), 'customer/account/login');

        }
    }
}
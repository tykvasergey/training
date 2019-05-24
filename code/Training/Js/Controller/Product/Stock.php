<?php

namespace Training\Js\Controller\Product;

use Magento\Framework\App\Action\Context;

class Stock extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonResultFactory;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $stockItemRepository;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    )
    {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->stockItemRepository = $stockItemRepository;

        parent::__construct($context);
    }

    public function execute()
    {
        $data = [];
        $params = $this->getRequest()->getParams();
        if(isset($params['product_id'])) {
            $qty = $this->stockItemRepository->get($params['product_id'])->getQty();
            $data['qty'] = $qty;
        }

        $result = $this->jsonResultFactory->create();
        $result->setData($data);

        return $result;
    }

}
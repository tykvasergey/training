<?php

namespace Training\FeedbackProduct\Controller\Index;


class Save extends \Magento\Framework\App\Action\Action
{
    protected $feedbackFactory;
    protected $feedbackResource;
    protected $feedbackDataLoader;

    /**
     * Save constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Training\Feedback\Model\FeedbackFactory $feedbackFactory
     * @param \Training\Feedback\Model\ResourceModel\Feedback $feedbackResource
     * @param \Training\FeedbackProduct\Model\FeedbackDataLoader $feedbackDataLoader
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Training\Feedback\Model\FeedbackFactory $feedbackFactory,
        \Training\Feedback\Model\ResourceModel\Feedback $feedbackResource,
        \Training\FeedbackProduct\Model\FeedbackDataLoader $feedbackDataLoader
    ) {
        $this->feedbackFactory = $feedbackFactory;
        $this->feedbackResource = $feedbackResource;
        $this->feedbackDataLoader = $feedbackDataLoader;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultRedirectFactory->create();
        if ($post = $this->getRequest()->getPostValue()) {
            try {
                $this->validatePost($post);
                $feedback = $this->feedbackFactory->create();
                $feedback->setData($post);
                $this->setProductsToFeedback($feedback, $post);
                $this->feedbackResource->save($feedback);
                $this->messageManager->addSuccessMessage(
                    __('Thank you for your feedback.')
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('An error occurred while processing your form. Please try again later.')
                );
                $result->setPath('*/*/form');
            }
        }
        $result->setPath('*/*/index');
        return $result;
    }

    /**
     * @param $feedback
     * @param $post
     */
    private function setProductsToFeedback($feedback, $post)
    {
        $skus = [];
        if (isset($post['products_skus']) && !empty($post['products_skus'])) {
            $skus = explode(',', $post['products_skus']);
            $skus = array_map('trim', $skus);
            $skus = array_filter($skus);
        }
        $this->feedbackDataLoader->addProductsToFeedbackBySkus($feedback, $skus);
    }

    /**
     * @param $post
     * @throws \Exception
     */
    private function validatePost($post)
    {
        if (!isset($post['author_name']) || trim($post['author_name']) === '') {
            throw new LocalizedException(__('Name is missing'));
        }
        if (!isset($post['message']) || trim($post['message']) === '') {
            throw new LocalizedException(__('Comment is missing'));
        }

        if (!isset($post['author_email']) || false === \strpos($post['author_email'], '@')) {
            throw new LocalizedException(__('Invalid email address'));
        }
        if (trim($this->getRequest()->getParam('hideit')) !== '') {
            throw new \Exception();
        }
    }
}
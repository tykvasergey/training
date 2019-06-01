<?php

namespace Training\Feedback\Controller\Index;

class Test extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Training\Feedback\Api\Data\FeedbackInterfaceFactory
     */
    private $feedbackFactory;

    /**
     * @var \Training\Feedback\Api\Data\FeedbackRepositoryInterface
     */
    private $feedbackRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * Test constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Training\Feedback\Api\Data\FeedbackInterfaceFactory $feedbackFactory
     * @param \Training\Feedback\Api\Data\FeedbackRepositoryInterface $feedbackRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Training\Feedback\Api\Data\FeedbackInterfaceFactory $feedbackFactory,
        \Training\Feedback\Api\Data\FeedbackRepositoryInterface $feedbackRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
    ) {
        $this->feedbackFactory = $feedbackFactory;
        $this->feedbackRepository = $feedbackRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        // create new item
        $newFeedback = $this->feedbackFactory->create();
        $newFeedback->setAuthorName('some name');
        $newFeedback->setAuthorEmail('test@test.com');
        $newFeedback->setMessage('ghj dsghjfghs sghkfgsdhfkj sdhjfsdf gsfkj');
        $newFeedback->setIsActive(1);
        $this->feedbackRepository->save($newFeedback);

        // load item by id
        try{
            $feedback = $this->feedbackRepository->getById(1);
            $this->printFeedback($feedback);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            exit('code:' . $e->getCode() . ', message: '. $e->getMessage());
        }

        // update item
        try{
            $feedbackToUpdate = $this->feedbackRepository->getById(1);
            $feedbackToUpdate->setMessage('CUSTOM ' . $feedbackToUpdate->getMessage());
            $this->feedbackRepository->save($feedbackToUpdate);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            exit('code:' . $e->getCode() . ', message: '. $e->getMessage());
        }

        // delete feedback
        $this->feedbackRepository->deleteById(1);

        // load multiple items
        $this->searchCriteriaBuilder
            ->addFilter('is_active', 1);

        $sortOrder = $this->sortOrderBuilder
            ->setField('author_name')
            ->setAscendingDirection()
            ->create();
        $this->searchCriteriaBuilder->addSortOrder($sortOrder);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResult = $this->feedbackRepository->getList($searchCriteria);

        try{
            foreach ($searchResult->getItems() as $item) {
                $this->printFeedback($item);
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            exit('code:' . $e->getCode() . ', message: '. $e->getMessage());
        }
        exit();
    }

    /**
     * @param $feedback
     */
    private function printFeedback($feedback)
    {
        echo $feedback->getId() . ' : '
            . $feedback->getAuthorName()
            . ' (' . $feedback->getAuthorEmail() . ')';
        echo "<br/>\n";
    }
}
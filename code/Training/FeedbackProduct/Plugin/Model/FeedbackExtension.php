<?php

namespace Training\FeedbackProduct\Plugin\Model;


class FeedbackExtension
{
    /**
     * @var \Training\Feedback\Api\Data\FeedbackExtensionInterfaceFactory
     */
    private $extensionAttributesFactory;

    public function __construct(
        \Training\Feedback\Api\Data\FeedbackExtensionInterfaceFactory $extensionAttributesFactory
    ) {
        $this->extensionAttributesFactory = $extensionAttributesFactory;
    }

    /**
     * @param \Training\Feedback\Api\Data\FeedbackInterface $subject
     * @param $result
     * @return \Training\Feedback\Api\Data\FeedbackExtensionInterface
     */
    public function afterGetExtensionAttributes(
        \Training\Feedback\Api\Data\FeedbackInterface $subject,
        $result
    ) {
        if (!is_null($result)) {
            return $result;
        }
        /** @var \Training\Feedback\Api\Data\FeedbackExtensionInterface $extensionAttributes */
        $extensionAttributes = $this->extensionAttributesFactory->create();
        $subject->setExtensionAttributes($extensionAttributes);
        return $extensionAttributes;
    }
}
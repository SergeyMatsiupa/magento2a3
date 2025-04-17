<?php

namespace Overdose\LessonOne\Controller\Adminhtml\Lesson;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Overdose\LessonOne\Model\LessonOneFactory;
use Overdose\LessonOne\Model\ResourceModel\LessonOne as LessonOneResource;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 *
 * Controller for saving lesson data
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var LessonOneFactory
     */
    protected $lessonOneFactory;
    /**
     * @var LessonOneResource
     */
    protected $lessonOneResource;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param LessonOneFactory $lessonOneFactory
     * @param LessonOneResource $lessonOneResource
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        LessonOneFactory $lessonOneFactory,
        LessonOneResource $lessonOneResource,
        LoggerInterface $logger
    ) {
        // Call parent constructor to initialize the context
        parent::__construct($context);
        // Assign LessonOneFactory to class property
        $this->lessonOneFactory = $lessonOneFactory;
        $this->lessonOneResource = $lessonOneResource;
        $this->logger = $logger;
    }

    /**
     * Execute save action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        // Get the request data (POST parameters)
        $data = $this->getRequest()->getPostValue();
            $this->logger->debug('Save.php received data: ' . json_encode($data));

        // Check for file data
        $fileData = isset($data['file']) && is_array($data['file']) && !empty($data['file']) ? $data['file'] : [];
        if (!empty($fileData)) {
            $fileInfo = $fileData[0]; // fileUploader sends an array of files
            $data['file_name'] = $fileInfo['file'] ?? null;
            $data['file_size'] = $fileInfo['size'] ?? null;
            $this->logger->debug('File data received: ' . json_encode($fileInfo));
            } else {
                $this->logger->debug('No file data received.');
            }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            if (!$this->lessonOneResource) {
                throw new \Exception('LessonOneResource is null.');
            }
            $lesson = $this->lessonOneFactory->create();
            $this->logger->debug('LessonOne save called with data: ' . json_encode($data));
            $lesson->setData($data);

            $this->logger->debug('ResourceModel LessonOne save called with data: ' . json_encode($lesson->getData()));
            $this->lessonOneResource->save($lesson);

            $this->logger->debug('Inserted new record with ID: ' . $lesson->getId());
            $this->logger->debug('ResourceModel LessonOne saved with ID: ' . $lesson->getId());
            $this->logger->debug('LessonOne saved successfully with ID: ' . $lesson->getId());
            $this->messageManager->addSuccessMessage(__('Lesson saved successfully.'));
            return $resultRedirect->setPath('*/*/index');
        } catch (\Exception $e) {
            $this->logger->error('Error saving lesson: ' . $e->getMessage());
            $this->messageManager->addErrorMessage(__('Error saving lesson: %1', $e->getMessage()));
            return $resultRedirect->setPath('*/*/edit', ['lesson_id' => $this->getRequest()->getParam('lesson_id')]);
        }
    }

    /**
     * Check if action is allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Overdose_LessonOne::lessonone_lesson');
    }
}
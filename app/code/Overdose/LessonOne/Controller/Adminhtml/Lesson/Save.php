<?php

namespace Overdose\LessonOne\Controller\Adminhtml\Lesson;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Overdose\LessonOne\Model\LessonOneFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 *
 * Controller for saving lesson data
 */
class Save extends Action
{
    /**
     * @var LessonOneFactory
     */
    protected $lessonOneFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param Context $context
     * @param LessonOneFactory $lessonOneFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        LessonOneFactory $lessonOneFactory,
        LoggerInterface $logger
    ) {
        // Call parent constructor to initialize the context
        parent::__construct($context);
        // Assign LessonOneFactory to class property
        $this->lessonOneFactory = $lessonOneFactory;
        $this->logger = $logger;
    }

    /**
     * Execute action to save lesson data
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        // Get the request data (POST parameters)
        $data = $this->getRequest()->getPostValue();
        // Create a redirect result object
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            // Log the incoming data for debugging
            $this->logger->debug('Save.php received data: ' . json_encode($data));

            if (empty($data)) {
                throw new \Exception('No data received to save.');
            }
            $model = $this->lessonOneFactory->create();

            // Process file data from fileUploader response
            if (isset($data['file']) && is_array($data['file']) && !empty($data['file'][0]['file'])) {
                $fileData = $data['file'][0];
                $data['file_name'] = $fileData['file']; // File name
                $data['file_size'] = isset($fileData['size']) ? (int) $fileData['size'] : 0; // File size in bytes
                $this->logger->debug('File data processed: ' . json_encode($fileData));
            } else {
                unset($data['file']); // Remove file field if no file was uploaded
                $this->logger->debug('No file data received.');
            }

            // Remove form_key from data to avoid saving it in the database
            if (isset($data['form_key'])) {
                unset($data['form_key']);
            }
            // Set data to the model from POST request
            $model->setData($data);
            // Save the model to the database
            $model->save();
            // Add success message
            $this->messageManager->addSuccessMessage(__('Lesson saved successfully.'));
            // Set redirect path to the lesson index page
            $resultRedirect->setPath('lessonone/lesson/index');
        } catch (\Exception $e) {
            $this->logger->error('Error saving lesson: ' . $e->getMessage());
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the lesson: %1', $e->getMessage()));
            // Set redirect path back to the edit page
            $resultRedirect->setPath('lessonone/lesson/edit');
        }

        // Return the redirect result
        return $resultRedirect;
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
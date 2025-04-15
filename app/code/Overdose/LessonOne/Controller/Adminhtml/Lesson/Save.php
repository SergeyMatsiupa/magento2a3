<?php

namespace Overdose\LessonOne\Controller\Adminhtml\Lesson;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Overdose\LessonOne\Model\LessonOneFactory;

class Save extends Action
{
    /**
     * @var LessonOneFactory
     */
    protected $lessonOneFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param LessonOneFactory $lessonOneFactory
     */
    public function __construct(
        Context $context,
        LessonOneFactory $lessonOneFactory
    ) {
        // Call parent constructor to initialize the context
        parent::__construct($context);
        // Assign LessonOneFactory to class property
        $this->lessonOneFactory = $lessonOneFactory;
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
            // Create a new LessonOne model instance
            $model = $this->lessonOneFactory->create();

            // Process the file uploaded through Upload.php
            if (isset($data['file']) && is_array($data['file']) && !empty($data['file'][0]['file'])) {
                $fileData = $data['file'][0];
                $data['file_name'] = $fileData['file']; // File name
                $data['file_size'] = $fileData['size']; // File size
                $data['file_path'] = $fileData['path']; // File path
            } else {
                unset($data['file']); // If the file is not uploaded, remove the field
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
            // Add error message if something goes wrong
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the lesson: %1', $e->getMessage()));
            // Set redirect path back to the edit page
            $resultRedirect->setPath('lessonone/lesson/edit');
        }

        // Return the redirect result
        return $resultRedirect;
    }

    /**
     * Authorization
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Overdose_LessonOne::lessonone_lesson');
    }
}
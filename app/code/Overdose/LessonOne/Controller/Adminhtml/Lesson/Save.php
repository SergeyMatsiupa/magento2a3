<?php

namespace Overdose\LessonOne\Controller\Adminhtml\Lesson;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
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
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var LessonOneFactory
     */
    protected $lessonOneFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param LessonOneFactory $lessonOneFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        LessonOneFactory $lessonOneFactory,
        LoggerInterface $logger
    ) {
        $this->dataPersistor = $dataPersistor;
        // Assign LessonOneFactory to class property
        $this->lessonOneFactory = $lessonOneFactory;
        $this->logger = $logger;
        // Call parent constructor to initialize the context
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // Get the request data (POST parameters)
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        $this->logger->debug('Save.php: Request data: ' . json_encode($data));

        if ($data) {
            try {
                $model = $this->lessonOneFactory->create();
                $id = $this->getRequest()->getParam('lesson_id');

                if ($id) {
                    $model->load($id);
                    if (!$model->getId()) {
                        $this->messageManager->addErrorMessage(__('This lesson no longer exists.'));
                        return $resultRedirect->setPath('*/*/');
                    }
                }

                // Обработка загруженного файла
                if (isset($data['file']) && !empty($data['file'][0]['name'])) {
                    $fileData = $data['file'][0];
                    $data['file_name'] = $fileData['name'];
                    $data['file_size'] = $fileData['size'];
                    $data['file'] = $fileData['file']; // Путь к файлу
                    $this->logger->debug('Save.php: File data processed: ' . json_encode($fileData));
                } else {
                    unset($data['file']);
                    $this->logger->debug('Save.php: No file data provided.');
                }

                $model->setData($data);
                $model->save();

                $this->messageManager->addSuccessMessage(__('You saved the lesson.'));
                $this->dataPersistor->clear('lessonone');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['lesson_id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->logger->error('Save.php: Error saving lesson: ' . $e->getMessage());
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set('lessonone', $data);
                return $resultRedirect->setPath('*/*/edit', ['lesson_id' => $id]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}

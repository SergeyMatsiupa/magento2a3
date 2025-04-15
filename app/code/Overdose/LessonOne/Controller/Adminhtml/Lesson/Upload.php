<?php
namespace Overdose\LessonOne\Controller\Adminhtml\Lesson;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\File\UploaderFactory;

class Upload extends Action
{
    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        UploaderFactory $uploaderFactory
    ) {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
    }

    /**
     * Execute file upload action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => 'file']);
            $uploader->setAllowedExtensions(['csv', 'xls', 'traffic']);
            $uploader->setAllowRenameFiles(true);
            $destination = $this->_getFilePath();
            $result = $uploader->save($destination);

            if (!$result) {
                throw new \Exception('File upload failed.');
            }

            // Include file name in response
            $response = [
                'file' => $result['file'], // Original file name after upload
                'size' => $result['size'],
                'path' => $destination . '/' . $result['file']
            ];
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($response);
            return $resultJson;
        } catch (\Exception $e) {
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData(['error' => $e->getMessage()]);
            return $resultJson;
        }
    }

    /**
     * Get file storage path
     *
     * @return string
     */
    protected function _getFilePath()
    {
        $directory = $this->_objectManager->get(\Magento\Framework\Filesystem::class)
            ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
        $path = $directory->getAbsolutePath('lessonone/files');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
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
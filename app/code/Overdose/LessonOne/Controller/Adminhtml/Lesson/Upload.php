<?php
namespace Overdose\LessonOne\Controller\Adminhtml\Lesson;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\File\UploaderFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Upload
 *
 * Controller for handling file uploads
 */
class Upload extends Action
{
    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param UploaderFactory $uploaderFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        UploaderFactory $uploaderFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->logger = $logger;
    }

    /**
     * Execute file upload action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        try {
            $this->logger->debug('Upload.php called with fileId: file');
            $uploader = $this->uploaderFactory->create(['fileId' => 'file']);
            $uploader->setAllowedExtensions(['csv', 'xls', 'traffic']);
            $uploader->setAllowRenameFiles(true);
            $destination = $this->_getFilePath();
            $result = $uploader->save($destination);

            if (!$result) {
                throw new \Exception('File upload failed.');
            }

            // Format response for fileUploader
            $response = [
                'name' => $result['file'],
                'size' => $result['size'],
                'url' => $destination . '/' . $result['file'],
                'error' => 0
            ];
            $this->logger->debug('Upload successful: ' . json_encode($response));
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($response);
            return $resultJson;
        } catch (\Exception $e) {
            $this->logger->error('Upload error: ' . $e->getMessage());
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData([
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode()
            ]);
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
     * Check if action is allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Overdose_LessonOne::lessonone_lesson');
    }
}
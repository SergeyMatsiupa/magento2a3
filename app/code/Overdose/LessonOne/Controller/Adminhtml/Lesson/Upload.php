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
        $this->logger->debug('Upload.php: Request received. Parameters: ' . json_encode($this->getRequest()->getParams()));
        $this->logger->debug('Upload.php: Files received: ' . json_encode($_FILES));
        try {
            if (!isset($_FILES['file'])) {
                throw new \Exception('No file uploaded.');
            }
            $this->logger->debug('Upload.php called with fileId: file');
            $uploader = $this->uploaderFactory->create(['fileId' => 'file']);
            $uploader->setAllowedExtensions(['csv', 'xls', 'traffic']);
            $uploader->setAllowRenameFiles(true);
            $destination = $this->_getFilePath();
            $this->logger->debug('Destination path: ' . $destination);
            $result = $uploader->save($destination);

            $this->logger->debug('Upload result: ' . json_encode($result));
            if (!$result || !isset($result['file']) || !isset($result['size'])) {
                throw new \Exception('File upload failed or invalid result: ' . json_encode($result));
            }

            // Normalize path for Windows compatibility
            $filePath = realpath($destination . '/' . $result['file']);
            if (!$filePath) {
                throw new \Exception('Failed to get real path for uploaded file.');
            }
            // Get file size directly from the file system
            $fileSize = filesize($filePath);
            if ($fileSize === false) {
                throw new \Exception('Failed to get file size.');
            }
            // Format response to match fileUploader expectations
            $response = [
                'name' => $result['file'],
                'size' => $fileSize,
                'file' => $result['file'],
                'path' => str_replace('\\', '/', $filePath), // Changed 'url' to 'path' to match Magento's default fileUploader expectations
                'type' => $uploader->getFileMimeType() ?? 'application/octet-stream',
                'error' => 0, // Changed to 0 to match fileUploader expectations
            ];
            $this->logger->debug('Upload successful. Response: ' . json_encode($response));
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($response);
            return $resultJson;
        } catch (\Exception $e) {
            $this->logger->error('Upload error: ' . $e->getMessage());
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData([
                'error' => 1, // Changed to 1 to match fileUploader expectations
                'errorcode' => $e->getCode(),
                'message' => $e->getMessage()
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
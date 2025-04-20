<?php
namespace Overdose\LessonOne\Controller\Adminhtml\Lesson;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Upload
 *
 * Controller for handling file uploads
 */
class Upload extends Action implements HttpPostActionInterface
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
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor
     *
     * @param Context $context
     * @param UploaderFactory $uploaderFactory
     * @param LoggerInterface $logger
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        UploaderFactory $uploaderFactory,
        LoggerInterface $logger,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->logger = $logger;
        $this->filesystem = $filesystem;
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
            $uploader->setFilesDispersion(false);
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
                'path' => str_replace('\\', '/', $filePath),
                'url' => $this->_getMediaUrl('lessonone/files/' . $result['file']),
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
        $directory = $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $path = $directory->getAbsolutePath('lessonone/files');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    /**
     * Get media URL for the file
     *
     * @param string $filePath
     * @return string
     */
    protected function _getMediaUrl($filePath)
    {
        return $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $filePath;
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
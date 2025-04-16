<?php

namespace Overdose\LessonOne\Model;

use Magento\Framework\Model\AbstractModel;
use Psr\Log\LoggerInterface;

/**
 * Class LessonOne
 *
 * Model for lesson entity
 */
class LessonOne extends AbstractModel
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param LoggerInterface $logger
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        LoggerInterface $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->logger = $logger;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Initialize the resource model for this entity
        $this->_init(\Overdose\LessonOne\Model\ResourceModel\LessonOne::class);
    }

    /**
     * Validate before saving
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $this->logger->debug('LessonOne beforeSave called with data: ' . json_encode($this->getData()));

        // Validate required fields
        if (empty($this->getTitle())) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Title is required.'));
        }
        if (empty($this->getContent())) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Content is required.'));
        }

        return parent::beforeSave();
    }

    /**
     * Save the model to the database
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save()
    {
        $this->logger->debug('LessonOne save called with data: ' . json_encode($this->getData()));
        try {
            $result = parent::save();
            $id = $this->getId();
            if (!$id) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Failed to save lesson: No ID returned after save.'));
            }
            $this->logger->debug('LessonOne saved successfully with ID: ' . $id);
            return $result;
        } catch (\Exception $e) {
            $this->logger->error('LessonOne save failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get lesson title
     *
     * @return string|null
     */
    public function getTitle()
    {
        // Retrieve the 'title' field from the model's data
        return $this->getData('title');
    }

    /**
     * Set lesson title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        // Set the 'title' field in the model's data
        return $this->setData('title', $title);
    }

    /**
     * Get lesson content
     *
     * @return string|null
     */
    public function getContent()
    {
        // Retrieve the 'content' field from the model's data
        return $this->getData('content');
    }

    /**
     * Set lesson content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        // Set the 'content' field in the model's data
        return $this->setData('content', $content);
    }

    /**
     * Get uploaded file name
     *
     * @return string|null
     */
    public function getFileName()
    {
        return $this->getData('file_name');
    }

    /**
     * Set uploaded file name
     *
     * @param string $fileName
     * @return $this
     */
    public function setFileName($fileName)
    {
        return $this->setData('file_name', $fileName);
    }

    /**
     * Get uploaded file size
     *
     * @return int|null
     */
    public function getFileSize()
    {
        return $this->getData('file_size');
    }

    /**
     * Set uploaded file size
     *
     * @param int $fileSize
     * @return $this
     */
    public function setFileSize($fileSize)
    {
        return $this->setData('file_size', $fileSize);
    }

}

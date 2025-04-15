<?php

namespace Overdose\LessonOne\Model;

use Magento\Framework\Model\AbstractModel;

class LessonOne extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        // Initialize the resource model for this entity
        $this->_init(\Overdose\LessonOne\Model\ResourceModel\LessonOne::class);
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

    public function getFileName()
    {
        return $this->getData('file_name');
    }

    public function setFileName($fileName)
    {
        return $this->setData('file_name', $fileName);
    }

    /**
     * Get file size
     *
     * @return int|null
     */
    public function getFileSize()
    {
        return $this->getData('file_size');
    }

    /**
     * Set file size
     *
     * @param int $size
     * @return $this
     */
    public function setFileSize($fileSize)
    {
        return $this->setData('file_size', $fileSize);
    }

    /**
     * Get file name
     *
     * @return string|null
     */
    public function getFileName()
    {
        return $this->getData('file_name');
    }

    /**
     * Set file name
     *
     * @param string $fileName
     * @return $this
     */
    public function setFileName($fileName)
    {
        return $this->setData('file_name', $fileName);
    }
}

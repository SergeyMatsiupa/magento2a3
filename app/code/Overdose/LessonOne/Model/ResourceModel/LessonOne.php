<?php
namespace Overdose\LessonOne\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class LessonOne extends AbstractDb
{
    /**
     * Define main table and primary key
     */
    protected function _construct()
    {
        // Initialize the main table and its primary key
        $this->_init('lesson_one', 'lesson_id');
    }
}
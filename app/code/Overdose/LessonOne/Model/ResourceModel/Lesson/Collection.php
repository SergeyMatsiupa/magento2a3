<?php
namespace Overdose\LessonOne\Model\ResourceModel\Lesson;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * Collection for LessonOne entity
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'lesson_id';
    protected function _construct()
    {
        $this->_init(
            \Overdose\LessonOne\Model\LessonOne::class,
            \Overdose\LessonOne\Model\ResourceModel\LessonOne::class
        );
    }
}
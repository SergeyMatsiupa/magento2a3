<?php
namespace Overdose\LessonOne\Model\ResourceModel\LessonOne;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * Collection for LessonOne entity
 */
class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Overdose\LessonOne\Model\LessonOne', 'Overdose\LessonOne\Model\ResourceModel\LessonOne');
        $this->_idFieldName = 'lesson_id';
    }

    /**
     * Load collection data
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        return parent::_afterLoad();
    }
}
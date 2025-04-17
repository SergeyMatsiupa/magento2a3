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
        $items = $this->getItems();
        $itemCount = count($items);
        $this->logger->debug('Collection _afterLoad called with item count: ' . $itemCount);
        foreach ($items as $item) {
            $this->logger->debug('Collection item: ' . json_encode($item->getData()));
        }
        return parent::_afterLoad();
    }
}
<?php
namespace Overdose\LessonOne\Model\ResourceModel\LessonOne;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define resource model and model
     *
     * @return void
     */
    protected function _construct()
    {
        // Initialize the collection with the model and resource model
        $this->_init(
            \Overdose\LessonOne\Model\LessonOne::class,  // Model class
            \Overdose\LessonOne\Model\ResourceModel\LessonOne::class  // Resource model class
        );
    }
}
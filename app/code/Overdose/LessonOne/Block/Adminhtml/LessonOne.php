<?php

namespace Overdose\LessonOne\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Overdose\LessonOne\Model\ResourceModel\LessonOne\CollectionFactory;
use Overdose\LessonOne\Model\Logger;  // Добавляем зависимость

class LessonOne extends Template
{

    /**
     * @var CollectionFactory  // Фабрика для коллекций
     */
    protected $collectionFactory;

    /**
     * @var \Overdose\LessonOne\Model\ResourceModel\LessonOne\Collection|null
     */
    protected $lessonCollection = null;  // Коллекция пока null

    /**
     * @var Logger  // Новый сервис
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param CollectionFactory $collectionFactory  // Фабрика из нашего модуля
     * @param Logger $logger  // Добавляем Logger через DI
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        CollectionFactory $collectionFactory,
        Logger $logger,  // Magento сам создаст и передаст объект Logger
        array $data = []
    ) {

        // echo "Constructor called, collection is not created yet.\n";
        // Assign Logger to class property
        $this->logger = $logger;
        // Assign CollectionFactory to class property
        $this->collectionFactory = $collectionFactory;
        // Здесь коллекция НЕ создаётся, только фабрика передана
        // Call parent constructor to initialize the block
        parent::__construct($context, $data);
    }


    /** 
     * Get the collection of lessons (lazy-loaded)
     *
     * @return \Overdose\LessonOne\Model\ResourceModel\LessonOne\Collection
     */
    public function getCollection()
    {
        // Create and return the lesson collection
        // Log when collection is fetched
        $this->logger->log(\Psr\Log\LogLevel::INFO, "Collection fetched at " . date('Y-m-d H:i:s'));
        // Lazy loading: создаём коллекцию только при первом вызове
        if ($this->lessonCollection === null) {
            // echo "Lazy loading: creating collection now.\n";
            $this->lessonCollection = $this->collectionFactory->create();   // Создаём коллекцию через фабрику
        }
        return $this->lessonCollection;
    }

    /**
     * Add button to toolbar
     * @param string $buttonId
     * @param array $buttonData
     * @return void
     */
    public function addButton($buttonId, $buttonData)
    {
        $childBlock = $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Button::class);
        $childBlock->setData($buttonData);
        $childBlock->setId($buttonId);
        $this->setChild($buttonId, $childBlock);
    }

    /**
     * Prepare layout
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        // Add "Add Lesson" button
        $this->addButton('add_lesson', [
            'label' => __('Add Lesson'),
            'onclick' => "setLocation('" . $this->getUrl('lessonone/lesson/edit') . "')",
            'class' => 'primary'
        ]);

        return $this;
    }



}

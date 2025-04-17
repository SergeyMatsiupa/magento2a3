<?php

namespace Overdose\LessonOne\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Overdose\LessonOne\Model\ResourceModel\LessonOne\CollectionFactory;
use Overdose\LessonOne\Model\Logger;  // Добавляем зависимость
use Psr\Log\LoggerInterface; // Добавляем LoggerInterface для отладки

/**
 * Class LessonOne
 *
 * Block for displaying lesson one list
 */
class LessonOne extends Template
{

    /**
     * @var CollectionFactory  // Фабрика для коллекций
     */
    protected $collectionFactory;

    /**
     * @var LoggerInterface
     */
    protected $lessonCollection = null;  // Коллекция пока null

    /**
     * @var Logger  // Новый сервис
     */
    protected $logger;

    /**
     * @var LoggerInterface // Добавляем для отладки
     */
    protected $_logger; // Добавляем поле для LoggerInterface

    /**
     * Constructor
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory  // Фабрика из нашего модуля
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        LoggerInterface $logger, // Добавляем LoggerInterface для отладки
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->_logger = $logger; // Инициализируем LoggerInterface
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
        // !msv
        // // Create and return the lesson collection
        // // Log when collection is fetched
        // $this->logger->log(\Psr\Log\LogLevel::INFO, "Collection fetched at " . date('Y-m-d H:i:s'));
        // // Lazy loading: создаём коллекцию только при первом вызове
        // if ($this->lessonCollection === null) {
        //     // echo "Lazy loading: creating collection now.\n";
        //     $this->lessonCollection = $this->collectionFactory->create();   // Создаём коллекцию через фабрику
        // }
        // return $this->lessonCollection;

        $collection = $this->collectionFactory->create();
        $collection->load();
        $this->_logger->debug('Collection size after load: ' . $collection->getSize());
        foreach ($collection as $item) {
            $this->_logger->debug('Collection item: ' . json_encode($item->getData()));
        }
        return $collection;
    }
}
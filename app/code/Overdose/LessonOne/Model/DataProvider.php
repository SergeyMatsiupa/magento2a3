<?php

namespace Overdose\LessonOne\Model;

use Overdose\LessonOne\Model\ResourceModel\Lesson\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Framework\App\RequestInterface;

class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collection;

    protected $dataPersistor;
    protected $loadedData;
    protected $request;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        RequestInterface $request,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        // Assign the collection factory to the property
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->request = $request;
        // Call parent constructor to initialize the data provider
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        // Debug log to confirm initialization
        \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Psr\Log\LoggerInterface::class)
            ->debug('DataProvider initialized: ' . $name);
    }

    /**
     * Get data for the form
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        // Initialize $loadedData as an empty array
        $this->loadedData = [];

        // Get the lesson_id from the request (e.g., from the URL parameter 'lesson_id')
        $lessonId = $this->request->getParam($this->getRequestFieldName());

        // Load existing lesson if lesson_id is provided
        if ($lessonId) {
            $lesson = $this->collection->getItemById($lessonId);
            if ($lesson && $lesson->getId()) {
                $data = $lesson->getData();
                if (isset($data['file']) && $data['file']) {
                    $data['file'] = [
                        [
                            'name' => $data['file_name'] ?? basename($data['file']),
                            'url' => $data['file'],
                            'size' => $data['file_size'] ?? 0,
                            'type' => 'file'
                        ]
                    ];
                }
                $this->loadedData['data'] = $data;
            } else {
                // If the lesson is not found, initialize an empty record
                $this->loadedData['data'] = [
                    'lesson_id' => $lessonId,
                    'title' => '',
                    'content' => '',
                    'file' => ''
                ];
            }
        }

        // Check if there is persisted data (e.g., after a failed form submission)
        $data = $this->dataPersistor->get('lessonone');
        if (!empty($data)) {
            $item = $this->collection->getNewEmptyItem();
            $item->setData($data);
            $persistedData = $item->getData();
            if (isset($persistedData['file']) && is_array($persistedData['file'])) {
                $persistedData['file'] = [
                    [
                        'name' => $persistedData['file_name'] ?? '',
                        'url' => $persistedData['file'] ?? '',
                        'size' => $persistedData['file_size'] ?? 0,
                        'type' => 'file'
                    ]
                ];
            }
            $this->loadedData['data'] = $persistedData;
            $this->dataPersistor->clear('lessonone');
        }

        // If no data is loaded (new lesson), provide an empty record
        if (empty($this->loadedData)) {
            $this->loadedData['data'] = [
                'lesson_id' => '',
                'title' => '',
                'content' => '',
                'file' => ''
            ];
        }

        // Temporary logging to debug if getData is called
        \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Psr\Log\LoggerInterface::class)
            ->debug('DataProvider getData called. Result: ' . json_encode($this->loadedData));

        return $this->loadedData;
    }
}

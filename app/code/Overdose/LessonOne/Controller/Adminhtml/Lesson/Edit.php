<?php
namespace Overdose\LessonOne\Controller\Adminhtml\Lesson;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Magento\Framework\Exception\LocalizedException;

class Edit extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Execute action to display the edit lesson page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // Log execution for debugging
        \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Psr\Log\LoggerInterface::class)
            ->debug('Edit controller execute called.');

        // Get lesson_id from request
        $id = $this->getRequest()->getParam('lesson_id');
        $model = $this->_objectManager->create(\Overdose\LessonOne\Model\Lesson::class);

        // Load existing lesson if ID is provided
        if ($id) {
            try {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This lesson no longer exists.'));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/');
                }
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        // Register model in registry
        $this->_coreRegistry->register('lessonone_lesson', $model);

        // Create result page
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Overdose_LessonOne::lessonone_lesson')
            ->addBreadcrumb(__('Lessons'), __('Lessons'))
            ->addBreadcrumb(
                $id ? __('Edit Lesson') : __('New Lesson'),
                $id ? __('Edit Lesson') : __('New Lesson')
            );

        // Set page title
        $resultPage->getConfig()->getTitle()->prepend(__('Lessons'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Lesson'));

        return $resultPage;
    }

    /**
     * Authorization check
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Overdose_LessonOne::lesson');
    }
}
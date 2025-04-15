<?php
namespace Overdose\LessonOne\Controller\Adminhtml\Lesson;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        // Call parent constructor to initialize the context
        parent::__construct($context);
        // Assign PageFactory to class property
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute action to display the admin lesson index page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        // Create a new page object using PageFactory
        $resultPage = $this->resultPageFactory->create();
        // Set the page title in the admin panel
        $resultPage->getConfig()->getTitle()->prepend(__('Lessons'));
        // Return the page to be rendered
        return $resultPage;
    }
}
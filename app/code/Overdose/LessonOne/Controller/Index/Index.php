<?php
namespace Overdose\LessonOne\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
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
        // Assign PageFactory to class property for later use
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute action to display the frontend page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        // Create a new page object using PageFactory
        $resultPage = $this->resultPageFactory->create();
        // Return the page to be rendered
        return $resultPage;
    }
}
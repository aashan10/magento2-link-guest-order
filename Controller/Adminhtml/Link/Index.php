<?php


namespace Aashan\LinkGuestOrder\Controller\Adminhtml\Link;


use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    private $factory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $factory
     */
    public function __construct(Context $context, PageFactory $factory)
    {
        parent::__construct($context);
        $this->factory = $factory;
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $page = $this->factory->create();
        $page->getConfig()->getTitle()->set(__('Guest Orders'));
        return $page;
    }

}

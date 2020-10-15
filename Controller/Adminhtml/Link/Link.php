<?php


namespace Aashan\LinkGuestOrder\Controller\Adminhtml\Link;


use Aashan\LinkGuestOrder\Helper\OrderLinkHelper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Link extends Action
{
    /**
     * @var OrderLinkHelper
     */
    private $helper;

    public function __construct(Context $context, OrderLinkHelper $helper)
    {
        parent::__construct($context);
        $this->helper = $helper;
    }

    public function execute()
    {
        $this->helper->processGuestOrders();
        return $this->_redirect($this->_redirect->getRefererUrl());
    }

}

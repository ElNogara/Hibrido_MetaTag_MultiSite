<?php
namespace Hibrido\MetaTag\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Cms\Model\Page;
use Magento\Store\Model\StoreManagerInterface;

class MetaTag extends Template
{
    protected $pageModel;
    protected $storeManager;

    public function __construct(
        Context $context,
        Page $pageModel,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->pageModel = $pageModel;
        $this->storeManager = $storeManager;

    }

 //Function responsible for returning all storeviews codes that the CMS page is used.
    public function getStoreCode(){   
        $page = $this->getCurrentPage();
        $storeIds = $page->getStoreId();
        if (!$storeIds) {
            return false;
        }
        foreach ($storeIds as $storeId) {
            $store = $this->storeManager->getStore($storeId);
            $storeViews[] = $store->getCode();
        }

        return $storeViews;
    }

//Function created to load the current CMS page using its ID.
    protected function getCurrentPage(){
        $pageId = $this->getRequest()->getParam('page_id');
        $currentPage = $this->pageModel->load($pageId);

        return $currentPage;
    }


    public function getCmsPageUrl(){
        $page = $this->getCurrentPage();
        $cmsPageUrl = $page->getIdentifier();
    
        return $cmsPageUrl;
    }

//Function responsible for getting all the basic urls of the store views.
    public function getStoreViewBaseUrl($storeViews)
    {
        foreach ($storeViews as $storeview) {
            $store = $this->storeManager->getStore($storeview);
            $baseUrls[] = $store->getBaseUrl();
        }

        return $baseUrls;
    }
}

<?php
namespace AHTG1\G1428\Block;
class SearchAutocomplete extends \Magento\Framework\View\Element\Template
{
    protected $_categoryCollectionFactory;
    protected $_storeManager;
    protected $_store;
    protected $_categoryRepository;
    protected $_myhelper;

    const DEFAULT_DISPLAY = false;
    const DEFAULT_NUMBER_PRODUCT_DISPLAY = 12;
    const DEFAULT_NUMBER_PRODUCT_OF_ROW = 4;


        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\Store $store,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \AHTG1\G1428\Helper\Data $helper,
        array $data = []
    )
    {
        $this->_categoryCollectionFactory 	=	$categoryCollectionFactory;
        $this->_storeManager 	=	$storeManager;
        $this->_store = $store;
        $this->_categoryRepository = $categoryRepository;
        $this->_myhelper = $helper;
        parent::__construct($context, $data);
    }
    //TODO: Yes/No
    public function getDisplay(){
            return $this->_myhelper->getGeneralConfig('yes');
    }
    //TODO: GetNumber
    public function getNumber(){
        if (!$this->_myhelper->getGeneralConfig('numberproduct')||$this->_myhelper->getGeneralConfig('numberproduct')==0) {
            # code...
            return self::DEFAULT_NUMBER_PRODUCT_DISPLAY;
        }
        return $this->_myhelper->getGeneralConfig('numberproduct');
    }
    //TODO: GetNumberOfRow

    public function getNumberOfRow(){
        if (!$this->_myhelper->getGeneralConfig('productofrow')) {
            # code...
            return self::DEFAULT_NUMBER_PRODUCT_OF_ROW;
        }
        return $this->_myhelper->getGeneralConfig('productofrow');
    }
    
    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        if ($isActive) {
            $collection->addIsActiveFilter();
        }
        if ($level) {
            $collection->addLevelFilter($level);
        }
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }
        if ($pageSize) {
            $collection->setPageSize($pageSize); 
        }
        $collection->setOrder('position', 'ASC');
        return $collection;
    }
    public function getAjaxUrl(){
		return $this->_storeManager->getStore()->getUrl('g1428/index/index');
	}
	public function getMediaUrl(){
		return $this ->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
	}
    public function getRootCategoryId()
    {
        return $this->_store->getStoreRootCategoryId();
    }
    public function getCategories(){
        return $this->_categoryRepository->get(2)->getChildrenCategories();
    }
}
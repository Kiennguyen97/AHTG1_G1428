<?php

namespace AHTG1\G1428\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_resultJsonFactory;
    protected $_productCollectionFactory;
    protected $_reviewFactory;
    protected $_storeManager;
    protected $_imageBuilder;
    protected $_productVisibility;
    protected $_categoryFactory;
    protected $_priceHelper;
    protected $_myhelper;

    const DEFAULT_DISPLAY = false;
    const DEFAULT_NUMBER_PRODUCT_DISPLAY = 12;
    const DEFAULT_NUMBER_PRODUCT_OF_ROW = 4;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \AHTG1\G1428\Helper\Data $helper
    ) {
        $this->_resultJsonFactory   =   $resultJsonFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_reviewFactory = $reviewFactory;
        $this->_storeManager = $storeManager;
        $this->_imageBuilder = $imageBuilder;
        $this->_productVisibility = $productVisibility;
        $this->_categoryFactory = $categoryFactory;
        $this->_priceHelper = $priceHelper;
        $this->_myhelper = $helper;
        parent::__construct($context);
    }
    public function execute()
    {
        $postMessage = $this->getRequest()->getPost();

        $query = preg_replace('/[^A-Za-z0-9\ \_\'\-]/', '', $postMessage['query']);
        $category = preg_replace('/[^a-z0-9]/', '', $postMessage['category']);


        if ($category == 'all') {
            $collection = $this->_productCollectionFactory->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('name', array('like' => '%' . $query . '%'));
        } else {
            $collection = $this->getProductCollection($category);
            $collection->addAttributeToFilter('name', array('like' => '%' . $query . '%'))->addAttributeToSort('relevance', 'desc');
        }
        $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());

        $collection->setPageSize($this->getNumber())
            ->setCurPage(1);
        $productList = [];
        $categoryList = [];
        $i = 1;
        $j = 1;
        foreach ($collection as $product) {
            $productList[$i]['name']        = str_ireplace($query, '<b><i>' . $query . '</i></b>', $product->getName());
            $productList[$i]['price']       = $this->_priceHelper->currency(number_format($product->getFinalPrice(), 2), true, false);
            $productList[$i]['url']         = $product->getProductUrl();
            $productList[$i]['thumbnail']   = $this->getImage($product, 'category_page_list')->getImageUrl();
            $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
            $productList[$i]['rating'] = $product->getRatingSummary()->getRatingSummary();
            $i++;
        }
        $data[1] = $productList;
        $data[2] = $categoryList;
        if ($collection->getSize() > 0) {
            return  $this->_resultJsonFactory->create()->setData($data);
        } else {
            return  $this->_resultJsonFactory->create()->setData([]);
        }
    }

    public function getImage($product, $imageId)
    {
        return $this->_imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->create();
    }

    //TODO: Yes/No
    public function getDisplay()
    {
        return $this->_myhelper->getGeneralConfig('yes');
    }

    //TODO: GetNumber
    public function getNumber()
    {
        if (!$this->_myhelper->getGeneralConfig('numberproduct') || $this->_myhelper->getGeneralConfig('numberproduct') == 0) {
            # code...
            return self::DEFAULT_NUMBER_PRODUCT_DISPLAY;
        }
        return $this->_myhelper->getGeneralConfig('numberproduct');
    }

    //TODO: GetNumberOfRow

    public function getNumberOfRow()
    {
        if (!$this->_myhelper->getGeneralConfig('productofrow')) {
            # code...
            return self::DEFAULT_NUMBER_PRODUCT_OF_ROW;
        }
        return $this->_myhelper->getGeneralConfig('productofrow');
    }

    public function getCategory($categoryId)
    {
        $category = $this->_categoryFactory->create()->load($categoryId);
        return $category;
    }

    public function getProductCollection($categoryId)
    {
        return $this->getCategory($categoryId)->getProductCollection()->addAttributeToSelect('*');
    }
}

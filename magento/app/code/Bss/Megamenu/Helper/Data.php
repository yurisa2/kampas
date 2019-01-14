<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_MegaMenu
 * @author     Extension Team
 * @copyright  Copyright (c) 2016-2017 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace Bss\Megamenu\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Bss\Megamenu\Model\ConfigFactory
     */
    protected $configFactory;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Bss\Megamenu\Model\ConfigFactory $configFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Request\Http $request,
        \Bss\Megamenu\Model\ConfigFactory $configFactory
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->request = $request;
        $this->categoryRepository = $categoryRepository;
        $this->storeManager = $storeManager;
        $this->configFactory = $configFactory;
        parent::__construct($context);
    }

    /**
     * @param string $config
     * @return bool|mixed
     */
    public function getConfig($config = '')
    {
        if ($config == '') {
            return false;
        }
        return $this->scopeConfig->getValue(
            'megamenu/general/' . $config,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isHomeUrl()
    {
        if ($this->request->getFullActionName() == 'cms_index_index') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $menu
     * @return string
     */
    public function getCustomCss($menu)
    {
        $customCss = '';
        if (isset($menu['custom_css']) && $menu['custom_css'] != '') {
            $customCss = $menu['custom_css'];
        }
        return $customCss;
    }

    /**
     * @param $page
     * @return string
     */
    public function getPageUrl($page)
    {
        return $this->_urlBuilder->getUrl(null, ['_direct' => $page->getIdentifier()]);
    }

    /**
     * @param $menu
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLinkUrl($menu)
    {
        $link = '#';
        switch ($menu['url_type']) {
            case 1:
                if ($menu['custom_link'] != '') {
                    $link = $this->storeManager->getStore()->getUrl($menu['custom_link']);
                }
                break;

            case 0:
                if ($menu['category_id'] != '' && $menu['category_id'] > 0) {
                    $category = $this->categoryRepository
                        ->get($menu['category_id'], $this->storeManager->getStore()->getId());
                    $link = $category->getUrl();
                }
        }
        return $link;
    }

    /**
     * @param $label
     * @return string
     */
    public function getLabelColor($label)
    {
        $html = '';
        switch ($label) {
            case 'new':
                $html = '&nbsp;&nbsp;<span class="label label-info">New</span>';
                break;

            case 'hot':
                $html = '&nbsp;&nbsp;<span class="label label-danger">Hot</span>';
                break;

            case 'sale':
                $html = '&nbsp;&nbsp;<span class="label label-success">Sales</span>';
        }
        return $html;
    }

    /**
     * @param $menu
     * @return float|int
     */
    public function checkSize($menu)
    {
        $size = 1;
        if ($menu['block_right'] != '') {
            $size++;
        }
        if ($menu['block_left'] != '') {
            $size++;
        }
        return 12 / $size;
    }

    /**
     * @param null $storeId
     * @return mixed|string
     */
    public function getMegaMenuConfig($storeId = null)
    {
        try {
            if ($storeId == null) {
                $storeId = 0;
            }
            $configFactory = $this->configFactory->create();
            $configCollection = $configFactory->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('path', 'megamenu/tree/data')
                ->addFieldToFilter('scope_id', $storeId)->getLastItem();
            $menu = $configCollection->getData('value');
        } catch (\Exception $e) {
            $menu = 'Error!';
        }

        return $menu;
    }
}

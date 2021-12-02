<?php
namespace InfinityRedux\UrlOverride\Helper;


class CatalogSeoConfig extends AbstractConfig
{
    public static string $section = 'catalog';
    public static string $group = 'seo';

    // See 'module-catalog-url-rewrite' system.xml for definitions.

    public function getCategoryUrlSuffix()     : ?string { return $this->_getConfig('category_url_suffix'); }
    public function getProductUrlSuffix()      : ?string { return $this->_getConfig('product_url_suffix'); }
    public function isCategoryProductRewrite() : bool    { return $this->_getConfig('generate_category_product_rewrites'); }
    public function isCategorySuffixActive()   : bool    { return $this->getCategoryUrlSuffix() !== null; }
    public function isProductSuffixActive()    : bool    { return $this->getProductUrlSuffix() !== null; }
    public function isProductUseCategoryPath() : bool    { return $this->_getConfig('product_use_categories'); }
    public function isSaveCreateRedirect()     : bool    { return $this->_getConfig('save_rewrites_history'); }
}
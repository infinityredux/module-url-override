<?php
namespace InfinityRedux\UrlOverride\Helper;


class UrlOverrideSuffixConfig extends AbstractConfig
{
    public static string $group = 'suffix';

    public function getCategorySuffix() : ?string { return $this->_getConfig('category_suffix'); }
    public function getProductSuffix()  : ?string { return $this->_getConfig('product_suffix'); }
}

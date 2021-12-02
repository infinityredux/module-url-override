<?php
namespace InfinityRedux\UrlOverride\Helper;


class UrlOverrideGeneralConfig extends AbstractConfig
{
    public static string $group = 'general';

    public function isEnabled()         : bool { return $this->_getConfig('enable'); }
    public function isCategoryEnabled() : bool { return $this->_isFieldEnabled('categories'); }
    public function isProductEnabled()  : bool { return $this->_isFieldEnabled('products'); }
}

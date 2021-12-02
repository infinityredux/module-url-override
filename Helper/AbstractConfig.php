<?php
namespace InfinityRedux\UrlOverride\Helper;
use BadMethodCallException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;


/**
 * Abstract helper class, for accessing server configuration.
 */
abstract class AbstractConfig
{
    public static string $section = 'ir_url_override';
    public static string $group = '';

    protected ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /***
     * Helper function used internally by the public functions to actually fetch
     * the requested configuration field.
     *
     * @param string $field The configuration field that is being accessed.
     * @param int|string|null $storeId The store id where the configuration is
     *  being obtained.
     *
     * @return array|int|string|boolean The value from the system config, with
     *  type determined by the specific field being fetched.
     *
     * @throws BadMethodCallException If a subclass has attempted to access a
     *  field without defining a group.
     */
    protected function _getConfig(string $field, $storeId = null)
    {
        if (static::$group === '')
            throw new BadMethodCallException('Subclass must define the group being accessed.');
        $path = implode('/', [static::$section, static::$group, $field]);
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Helper function to fetch configuration where the result should depend not
     * just on the value of the field itself but also the 'enable' field for
     * this section.
     *
     * @param string $field The boolean configuration field being accessed.
     * @param int|string|null $storeId The store id where the configuration is
     *  being obtained.
     *
     * @return bool True only if the provided field and the 'enable' field are
     *  both true values; otherwise false;
     */
    protected function _isFieldEnabled(string $field, $storeId = null): bool
    {
        return ($this->_getConfig('enable', $storeId) and $this->_getConfig($field, $storeId));
    }
}

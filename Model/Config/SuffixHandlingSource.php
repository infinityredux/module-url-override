<?php
namespace InfinityRedux\UrlOverride\Model\Config;
use Magento\Framework\Data\OptionSourceInterface;


class SuffixHandlingSource implements OptionSourceInterface
{
    public const DISABLE = 0;
    public const ADD_SUFFIX = 1;
    public const ADD_SECONDARY = 2;

    public function toOptionArray(): array
    {
        return [
            ['value' => self::DISABLE, 'label' => __('Disable Suffix Processing')],
            ['value' => self::ADD_SUFFIX, 'label' => __('Add Suffix to Existing')],
            ['value' => self::ADD_SECONDARY, 'label' => __('Create Secondary URLs')],
        ];
    }
}
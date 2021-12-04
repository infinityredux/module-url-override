<?php
namespace InfinityRedux\UrlOverride\Operations;
use InfinityRedux\UrlOverride\Model\RebuildContext;
use InfinityRedux\UrlOverride\Model\RebuildOperation;
use Magento\Catalog\Model\Category;
use Magento\Framework\DB\Adapter\AdapterInterface;


class RemoveSuffixUrlPathOperation implements RebuildOperation
{
    static public function execute(RebuildContext   $context,
                                   AdapterInterface $connection)
    : int
    {
        $tableName = $connection->getTableName('catalog_category_entity_varchar');
        $pathId = $context->getAttributeId(Category::ENTITY, 'url_path');

        $result = $connection->query(
            "UPDATE $tableName ".
            "SET value = REPLACE(value, '.html', '') ".
            "WHERE attribute_id = $pathId AND value LIKE '%html%';"
        );

        return $result->rowCount();
    }
}

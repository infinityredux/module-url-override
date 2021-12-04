<?php
namespace InfinityRedux\UrlOverride\Operations;
use InfinityRedux\UrlOverride\Model\RebuildContext;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite;


class RemoveDuplicatedCategoryOperation implements \InfinityRedux\UrlOverride\Model\RebuildOperation
{

    static public function execute(RebuildContext $context,
                                   AdapterInterface $connection)
    : int
    {
        $table = $connection->getTableName('url_rewrite');
        $category = Rewrite::ENTITY_TYPE_CATEGORY;

        $result = $connection->query(
            "-- noinspection SqlNoDataSourceInspection
            DELETE FROM $table
            WHERE entity_type = '$category' AND request_path = target_path;"
        );

        return $result->rowCount();
    }
}

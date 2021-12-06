<?php
namespace InfinityRedux\UrlOverride\Operations;
use InfinityRedux\UrlOverride\Model\RebuildContext;
use InfinityRedux\UrlOverride\Model\RebuildOperation;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite;


class DeleteOrphanProductRequestOperation implements RebuildOperation
{
    static public function execute(RebuildContext   $context,
                                   AdapterInterface $connection)
    : int
    {
        $table = $connection->getTableName('url_rewrite');
        $product = Rewrite::ENTITY_TYPE_PRODUCT;

        $result = $connection->query(
            "-- noinspection SqlNoDataSourceInspection
            DELETE FROM $table
            WHERE entity_type = '$product' AND request_path LIKE '%html%';"
        );

        return $result->rowCount();
    }
}

<?php
namespace InfinityRedux\UrlOverride\Operations;
use InfinityRedux\UrlOverride\Model\RebuildContext;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite;


class RemoveDuplicatedProductOperation implements \InfinityRedux\UrlOverride\Model\RebuildOperation
{

    static public function execute(RebuildContext $context,
                                   AdapterInterface $connection)
    : int
    {
        $table = $connection->getTableName('url_rewrite');
        $product = Rewrite::ENTITY_TYPE_PRODUCT;

        $result = $connection->query(
            "-- noinspection SqlNoDataSourceInspection
            DELETE FROM $table
            WHERE entity_type = '$product' AND request_path = target_path;"
        );

        return $result->rowCount();
    }
}

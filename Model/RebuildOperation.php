<?php
namespace InfinityRedux\UrlOverride\Model;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Zend_Db_Statement_Exception;


interface RebuildOperation
{
    /**
     * @param RebuildContext $context
     * @param AdapterInterface $connection
     *
     * @return int Number of rows updated by this operation.
     *
     * @throws Zend_Db_Statement_Exception If attempting to get the row count
     *  somehow errors, which typically means the operation has somehow failed.
     */
    static public function execute(RebuildContext $context,
                                   AdapterInterface $connection): int;
}
<?php
namespace InfinityRedux\UrlOverride\Operations;
use InfinityRedux\UrlOverride\Model\RebuildContext;
use InfinityRedux\UrlOverride\Model\RebuildOperation;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite as UrlRewriteData;


class GenerateMissingProductPerStoreOperation implements RebuildOperation
{
    static public function execute(RebuildContext   $context,
                                   AdapterInterface $connection)
    : int
    {
        $product = Rewrite::ENTITY_TYPE_PRODUCT;
        $fieldEntityType = UrlRewriteData::ENTITY_TYPE;
        $fieldEntityId = UrlRewriteData::ENTITY_ID;
        $fieldRequestPath = UrlRewriteData::REQUEST_PATH;
        $fieldTargetPath =  UrlRewriteData::TARGET_PATH;
        $fieldRedirectType = UrlRewriteData::REDIRECT_TYPE;
        $fieldStoreId = UrlRewriteData::STORE_ID;
        $fieldAutogenerated = UrlRewriteData::IS_AUTOGENERATED;

        $table = $connection->getTableName('url_rewrite');
        $stores = $context->getStores();
        $count = 0;

        foreach ($stores as $store) {
            $storeId = $store->getId();

            $productCollection = $context->getProductCollection();
            $productCollection->addFieldToSelect('entity_id');
            $productCollection->addStoreFilter($storeId);
            /** @var int[] $productEntityIds */
            $productEntityIds = $productCollection->getColumnValues('entity_id');

            $rewriteCollection = $context->getUrlRewriteCollection();
            $rewriteCollection->addFieldToFilter(UrlRewriteData::ENTITY_TYPE, $product);
            $rewriteCollection->addFieldToFilter(UrlRewriteData::STORE_ID, $storeId);
            $rewriteCollection->addFieldToSelect(UrlRewriteData::ENTITY_ID);
            /** @var int[] $rewriteCategories */
            $rewriteCategories = $rewriteCollection->getColumnValues(UrlRewriteData::ENTITY_ID);

            // Flip for efficient searching
            $rewriteCategories = array_flip($rewriteCategories);
            $missingIds = [];
            foreach ($productEntityIds as $entityId) {
                if ( !isset($rewriteCategories[$entityId]) ) {
                    $missingIds[] = $entityId;
                }
            }

            if (count($missingIds) === 0)
                continue;

            $productCollection = $context->getProductCollection();
            $productCollection->addFieldToFilter('entity_id', ['in' => $missingIds]);
//            $missingProducts = ->getData();

            $missingValues = [];
            /** @var Product $missingProduct */
            foreach ($productCollection as $missingProduct) {
                $missingProductId = $missingProduct->getEntityId();
                $temp = [];
                $temp[] = "'$product'";
                $temp[] = $missingProductId;
                $temp[] = $missingProduct->formatUrlKey($missingProduct->getName());
                // TODO: this is hard coding... but magento probably has a
                //  function to generate it? Maybe try and find/use that?
                $temp[] = "catalog/product/view/id/$missingProductId";
                $temp[] = 0;  // Redirect type
                $temp[] = $storeId;
                $temp[] = 1;  // Autogenerated
                $missingValues[] = '(' . implode(',', $temp) . ')';

                print_r("Testing: ");
                print_r($missingProductId);
                print_r(" ");
                print_r(implode(',', $missingProduct->getCategoryIds()));
                print_r(" - count: ");
                print_r($missingProduct->getCategoryCollection()->count());

                /** @var Category $linkedCategory */
                foreach ($missingProduct->getCategoryCollection() as $linkedCategory) {
                    $linkedCategoryId = $linkedCategory->getEntityId();
                    $temp = [];
                    $temp[] = "'$product'";
                    $temp[] = $missingProductId;
                    $temp[] = $linkedCategory->getUrlKey() . '/' .
                        $missingProduct->formatUrlKey($missingProduct->getName());
                    $temp[] = "catalog/product/view/id/$missingProductId/category/$linkedCategoryId";
                    $temp[] = 0;  // Redirect type
                    $temp[] = $storeId;
                    $temp[] = 1;  // Autogenerated
                    $missingValues[] = '(' . implode(',', $temp) . ')';
                }

            }
            $missingSql = implode(',', $missingValues);
            print_r("\n\n");
            print_r($missingSql);

//            $result = $connection->query(
//                "-- noinspection SqlNoDataSourceInspection
//                INSERT INTO $table (
//                    $fieldEntityType, $fieldEntityId, $fieldRequestPath,
//                    $fieldTargetPath, $fieldRedirectType, $fieldStoreId,
//                    $fieldAutogenerated
//                )
//                VALUES $missingSql"
//            );
//
//            $count += $result->rowCount();
        }

        return $count;
    }
}

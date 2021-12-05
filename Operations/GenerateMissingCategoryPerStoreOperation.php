<?php
namespace InfinityRedux\UrlOverride\Operations;
use InfinityRedux\UrlOverride\Model\RebuildContext;
use InfinityRedux\UrlOverride\Model\RebuildOperation;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite as UrlRewriteData;


class GenerateMissingCategoryPerStoreOperation implements RebuildOperation
{
    /**
     * @inerhitDoc
     * @throws NoSuchEntityException
     */
    static public function execute(RebuildContext   $context,
                                   AdapterInterface $connection)
    : int
    {
        $tableCategory = $connection->getTableName('catalog_category_entity');
        $tableRewrite = $connection->getTableName('url_rewrite');
        $category = Rewrite::ENTITY_TYPE_CATEGORY;

        $stores = $context->getStores();

        foreach ($stores as $store) {
            $storeId = $store->getId();
            $rootCategory = $store->getRootCategoryId();

            $categoryCollection = $context->getCategoryCollectionFactory();
            $categoryCollection->addFieldToFilter(CategoryInterface::KEY_PATH, ['like' => "1/$rootCategory/%"]);
            /** @var int[] $categoryIds */
            $categoryIds = $categoryCollection->getAllIds();

            $rewriteCollection = $context->getUrlRewriteCollection();
            $rewriteCollection->addFieldToFilter(UrlRewriteData::ENTITY_TYPE, $category);
            $rewriteCollection->addFieldToFilter(UrlRewriteData::STORE_ID, $storeId);
            $rewriteCollection->addFieldToSelect(UrlRewriteData::ENTITY_ID);
            /** @var int[] $rewriteCategories */
            $rewriteCategories = $rewriteCollection->getColumnValues(UrlRewriteData::ENTITY_ID);

            print_r("Store $storeId\n");
//            print_r($categoryIds);
//            print_r($rewriteCategories);

            // Flip for efficient searching
            $rewriteCategories = array_flip($rewriteCategories);

            $missing = [];
            foreach ($categoryIds as $categoryId) {
                if ( !isset($rewriteCategories[$categoryId]) ) {
                    $missing[] = $categoryId;
                }
            }

            print_r("Missing: ");
            print_r($missing);
        }

        return -1;
    }
}

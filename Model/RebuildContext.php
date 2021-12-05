<?php
namespace InfinityRedux\UrlOverride\Model;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use /** @noinspection PhpUndefinedClassInspection */
    Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute as AttributeResource;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection;
use /** @noinspection PhpUndefinedClassInspection */
    Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;


/**
 * @noinspection PhpUndefinedClassInspection
 */
class RebuildContext
{
    private AttributeResource $attributeResource;
    private StoreManagerInterface $storeManager;
    private UrlRewriteCollectionFactory $rewriteCollectionFactory;
    private CategoryCollectionFactory $categoryCollectionFactory;

    public function __construct(AttributeResource           $attributeResource,
                                StoreManagerInterface       $storeManager,
                                /** @noinspection PhpUndefinedClassInspection */
                                CategoryCollectionFactory   $categoryCollectionFactory,
                                /** @noinspection PhpUndefinedClassInspection */
                                UrlRewriteCollectionFactory $rewriteCollectionFactory)
    {
        $this->attributeResource = $attributeResource;
        $this->storeManager = $storeManager;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->rewriteCollectionFactory = $rewriteCollectionFactory;
    }

    public function getAttributeId(string $entity_type, string $code): int
    {
        return $this->attributeResource->getIdByCode($entity_type, $code);
    }

    /**
     * @return StoreInterface[]
     */
    public function getStores(): array
    {
        return $this->storeManager->getStores();
    }

    public function getCategoryCollectionFactory(): CategoryCollection
    {
        return $this->categoryCollectionFactory->create();
    }

    public function getUrlRewriteCollection(): UrlRewriteCollection
    {
        return $this->rewriteCollectionFactory->create();
    }
}

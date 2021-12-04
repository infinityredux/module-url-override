<?php
namespace InfinityRedux\UrlOverride\Model;

use Magento\Eav\Model\ResourceModel\Entity\Attribute as AttributeResource;

class RebuildContext
{
    private AttributeResource $attributeResource;

    public function __construct(AttributeResource $attributeResource)
    {
        $this->attributeResource = $attributeResource;
    }

    public function getAttributeId(string $entity_type, string $code): int {
        return $this->attributeResource->getIdByCode($entity_type, $code);
    }
}
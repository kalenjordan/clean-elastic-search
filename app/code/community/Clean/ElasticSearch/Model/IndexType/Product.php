<?php

class Clean_ElasticSearch_Model_IndexType_Product extends Clean_ElasticSearch_Model_IndexType_Abstract
{
    protected function _getIndexTypeCode()
    {
        return 'product';
    }

    protected function _getCollection()
    {
        $products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('description');

        return $products;
    }

    /**
     * @param $product Mage_Catalog_Model_Product
     * @return \Elastica\Document
     */
    protected function _prepareDocument($product)
    {
        $data = array(
            'id'            => $product->getId(),
            'name'          => $product->getName(),
            'description'   => $product->getData('description'),
        );

        $document = new \Elastica\Document($data['id'], $data);
        return $document;
    }
}
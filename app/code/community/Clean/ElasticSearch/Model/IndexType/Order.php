<?php

class Clean_ElasticSearch_Model_IndexType_Order extends Clean_ElasticSearch_Model_IndexType_Abstract
{
    protected function _getIndexTypeCode()
    {
        return 'order';
    }

    public function index()
    {
        Mage::getSingleton('cleanelastic/resource_iterator_batched')->walk(
            $this->_getCollection(),
            array(array($this, 'reindexOrder'))
        );
    }

    public function reindexOrder($order)
    {
        $type = Mage::getSingleton('cleanelastic/index')->getOrderType();
        $document = $this->_prepareDocument($order);
        $type->addDocument($document);
    }

    protected function _getCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }
        $orders = Mage::getResourceModel('sales/order_collection');

        $orders->getSelect()
            ->joinLeft(
                array('item' => $orders->getTable('sales/order_item')),
                'item.order_id = main_table.entity_id',
                array("GROUP_CONCAT(sku SEPARATOR ', ') AS sku_list")
            )
            ->group('main_table.entity_id');

        $this->_collection = $orders;
        return $this->_collection;
    }

    /**
     * @param $order Mage_Sales_Model_Order
     * @return \Elastica\Document
     */
    protected function _prepareDocument($order)
    {
        $data = array(
            'id'            => $order->getId(),
            'email'         => $order->getCustomerEmail(),
            'firstname'     => $order->getCustomerFirstname(),
            'lastname'      => $order->getCustomerLastname(),
            'fullname'      => $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname(),
            'increment_id'  => $order->getIncrementId(),
            'sku_list'      => $order->getData('sku_list')
        );

        $document = new \Elastica\Document($data['id'], $data);
        return $document;
    }
}
<?php

class Clean_ElasticSearch_Model_Indexer extends Mage_Index_Model_Indexer_Abstract
{
   public function getName()
    {
        return Mage::helper('core')->__('Elastic Search Cache');
    }

    public function getDescription()
    {
        return Mage::helper('core')->__('Elastic search');
    }

    protected function _construct()
    {
        $this->_init('cleanelastic/indexer');
    }

    protected function _registerEvent(Mage_Index_Model_Event $event)
    {
        // todokj register some stuff
    }

    protected function _processEvent(Mage_Index_Model_Event $event)
    {
        $data = $event->getNewData();
        if (!empty($data['catalog_product_eav_reindex_all'])) {
            $this->reindexAll();
        }
        if (empty($data['catalog_product_eav_skip_call_event_handler'])) {
            $this->callEventHandler($event);
        }
    }

    public function reindexAll()
    {
        Mage::getSingleton('cleanelastic/index')->deleteIndex();
        $this->_reindexAllOrders();
        Mage::getModel('cleanelastic/indexType_customer')->index();
        Mage::getModel('cleanelastic/indexType_config')->index();
    }

    protected function _getOrders()
    {
        $orders = Mage::getResourceModel('sales/order_collection');
        // $orders->setPageSize(2000);

        $orders->getSelect()
            ->joinLeft(
                array('item' => $orders->getTable('sales/order_item')),
                'item.order_id = main_table.entity_id',
                array("GROUP_CONCAT(sku SEPARATOR ', ') AS sku_list")
            )
            ->group('main_table.entity_id');

        return $orders;
    }

    protected function _reindexAllOrders()
    {
        Mage::getSingleton('core/resource_iterator')->walk(
            $this->_getOrders()->getSelect(),
            array(array($this, 'reindexOrder'))
        );
    }

    public function reindexOrder($data)
    {
        $type = Mage::getSingleton('cleanelastic/index')->getOrderType();
        $document = $this->_prepareOrderDocument($data['row']);
        $type->addDocument($document);
    }

    /**
     * @param $order Mage_Sales_Model_Order
     */
    protected function _prepareOrderDocument($orderData)
    {
        // $customer = $customer->load($customer->getId());
        $data = array(
            'id'            => $orderData['entity_id'],
            'email'         => $orderData['customer_email'],
            'firstname'     => $orderData['customer_firstname'],
            'lastname'      => $orderData['customer_lastname'],
            'fullname'      => $orderData['customer_firstname'] . ' ' . $orderData['customer_lastname'],
            'increment_id'  => $orderData['increment_id'],
            'sku_list'      => $orderData['sku_list'],
        );

        $document = new \Elastica\Document($orderData['entity_id'], $data);
        return $document;
    }
}

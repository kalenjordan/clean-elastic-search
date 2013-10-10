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
        $this->_deleteElasticaIndex();
        $this->_reindexAllCustomers();
    }

    protected function _getCustomers()
    {
        $customers = Mage::getResourceModel('customer/customer_collection');

        return $customers;
    }

    protected function _deleteElasticaIndex()
    {
        // todokj try / catch the case where index doesn't exist
        Mage::getSingleton('cleanelastic/index')->getIndex()->delete();
    }

    protected function _reindexAllCustomers()
    {
        $customerType = Mage::getSingleton('cleanelastic/index')->getCustomerType();
        Mage::getSingleton('cleanelastic/index')->create();

        foreach ($this->_getCustomers() as $customer) {
            $customerDocument = $this->_prepareCustomerDocument($customer);
            $customerType->addDocument($customerDocument);
        }
    }

    /**
     * @param $customer Mage_Customer_Model_Customer
     */
    protected function _prepareCustomerDocument($customer)
    {
        $customer = $customer->load($customer->getId());
        $data = array(
            'id'      => $customer->getId(),
            'firstname' => $customer->getData('firstname'),
            'lastname' => $customer->getData('lastname'),
        );

        $document = new \Elastica\Document($customer->getId(), $data);
        return $document;
    }
}

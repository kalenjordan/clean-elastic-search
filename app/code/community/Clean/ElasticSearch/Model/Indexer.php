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

    /**
     * Handling the incremental indexing in observers
     *
     * @see Clean_ElasticSearch_Model_Observer::customerSaveCommitAfter()
     * @param Mage_Index_Model_Event $event
     */
    protected function _registerEvent(Mage_Index_Model_Event $event) { }
    protected function _processEvent(Mage_Index_Model_Event $event) { }

    public function reindexAll()
    {
        Mage::getModel('cleanelastic/indexType_product')->index();
        Mage::getModel('cleanelastic/indexType_order')->index();
        Mage::getModel('cleanelastic/indexType_customer')->index();
        Mage::getModel('cleanelastic/indexType_config')->index();
    }
}

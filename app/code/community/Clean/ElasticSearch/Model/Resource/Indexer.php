<?php

class Clean_ElasticSearch_Model_Resource_Indexer extends Mage_Index_Model_Resource_Abstract
{
    protected function _construct()
    {
        $this->_init('cleanelastic/indexer', 'entity_id');
    }
}

<?php

class Clean_ElasticSearch_Model_Index extends Varien_Object
{
    public function getClient()
    {
        if (isset($this->_client)) {
            return $this->_client;

        }
        // todokj this should be configurable
        $elasticaClient = new \Elastica\Client(array(
            'host' => 'localhost',
            'port' => 9200
        ));

        $this->_client = $elasticaClient;
        return $this->_client;
    }

    /**
     * @return \Elastica\Index
     */
    public function getIndex()
    {
        if (isset($this->_index)) {
            return $this->_index;
        }

        $elasticaIndex = $this->getClient()->getIndex('magento');

        $this->_index = $elasticaIndex;
        return $this->_index;
    }

    public function create()
    {
        $index = $this->getIndex();
        $index->create(array(

        ));

        return $this;
    }

    /**
     * @return \Elastica\Type
     */
    public function getCustomerType()
    {
        return $this->getIndex()->getType('customer');
    }
}
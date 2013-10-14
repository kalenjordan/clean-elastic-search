<?php

class Clean_ElasticSearch_Model_Index extends Varien_Object
{
    public function getClient()
    {
        if (isset($this->_client)) {
            return $this->_client;

        }

        $elasticaClient = new \Elastica\Client(array(
            'host' => Mage::helper('cleanelastic')->getHost(),
            'port' => Mage::helper('cleanelastic')->getPort(),
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

    /**
     * @return \Elastica\Type
     */
    public function getCustomerType()
    {
        return $this->getIndex()->getType('customer');
    }

    /**
     * @return \Elastica\Type
     */
    public function getOrderType()
    {
        return $this->getIndex()->getType('order');
    }

    public function deleteIndex()
    {
        try {
            return $this->getIndex()->delete();
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'IndexMissingException') !== false) {
                // Do nothing
            } else {
                throw $e;
            }
        }
    }
}
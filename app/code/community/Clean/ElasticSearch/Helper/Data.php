<?php

class Clean_ElasticSearch_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getHost()
    {
        return (string)Mage::getConfig()->getNode('global/elasticsearch/host');

    }

    public function getPort()
    {
        return (string)Mage::getConfig()->getNode('global/elasticsearch/port');
    }

    public function getSecret()
    {
        return (string)Mage::getConfig()->getNode('global/elasticsearch/secret');
    }
}
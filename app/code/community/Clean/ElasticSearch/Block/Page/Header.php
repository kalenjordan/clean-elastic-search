<?php

class Clean_ElasticSearch_Block_Page_Header extends  Mage_Adminhtml_Block_Page_Header
{
    public function getUrl($route = '', $params = array())
    {
        if ($route == 'adminhtml/index/globalSearch') {
            return Mage::getStoreConfig('web/secure/base_url') . 'autocomplete.php';
        }

        return parent::getUrl($route, $params);
    }
}
<?php

class Clean_ElasticSearch_Block_Page_Header extends  Mage_Adminhtml_Block_Page_Header
{
    public function getUrl($route = '', $params = array())
    {
        if ($route == 'adminhtml/index/globalSearch') {
            $url = Mage::getStoreConfig('web/secure/base_url') . 'autocomplete.php';
            $url .= '?base_url=' . urlencode(Mage::helper('adminhtml')->getUrl('adminhtml'));

            return $url;
        }

        return parent::getUrl($route, $params);
    }
}
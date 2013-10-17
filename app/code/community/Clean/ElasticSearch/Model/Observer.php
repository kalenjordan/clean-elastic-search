<?php

class Clean_ElasticSearch_Model_Observer extends Varien_Object
{
    /**
     * @param $observer Varien_Event_Observer
     */
    public function customerSaveCommitAfter($observer)
    {
        /** @var Mage_Customer_Model_Customer $customer */
        $customer = $observer->getData('customer');

        if ($this->_needToIndexCustomer($customer)) {
            $this->_indexCustomer($customer);
        } else if ($this->_needToUpdateIndex($customer)) {
            $this->_updateCustomerIndex($customer);
        }

        return $this;
    }

    /**
     * @param $observer Varien_Event_Observer
     */
    public function salesOrderPlaceAfter($observer)
    {
        /** @var Mage_Sales_Model_Order $order */
        $order = $observer->getData('order');

        if ($this->_needToIndexOrder($order)) {
            $this->_indexOrder($order);
        }

        return $this;
    }

    /**
     * @param $order Mage_Sales_Model_Order
     */
    protected function _needToIndexOrder($order)
    {
        if (!$order->getOrigData()) {
            return true;
        }

        return false;
    }

    /**
     * @param $order Mage_Sales_Model_Order
     */
    protected function _indexOrder($order)
    {
        $indexType = Mage::getSingleton('cleanelastic/indexType_order');
        $indexType->addDocument($order);
    }

    /**
     * @param $customer Mage_Customer_Model_Customer
     */
    protected function _needToIndexCustomer($customer)
    {
        if (!$customer->getOrigData()) {
            return true;
        }

        return false;
    }

    /**
     * @param $customer Mage_Customer_Model_Customer
     */
    protected function _needToUpdateIndex($customer)
    {
        $fields = array('firstname', 'lastname', 'email');
        foreach ($fields as $field) {
            if ($customer->getData($field) != $customer->getOrigData($field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $customer Mage_Customer_Model_Customer
     */
    protected function _updateCustomerIndex($customer)
    {
        $indexType = Mage::getSingleton('cleanelastic/indexType_customer');
        $indexType->updateDocument($customer);
    }

    /**
     * @param $customer Mage_Customer_Model_Customer
     */
    protected function _indexCustomer($customer)
    {
        $indexType = Mage::getSingleton('cleanelastic/indexType_customer');
        $indexType->addDocument($customer);
    }
}
<?php

class Clean_ElasticSearch_Model_IndexType_Customer extends Clean_ElasticSearch_Model_IndexType_Abstract
{
    protected function _getIndexTypeCode()
    {
        return 'customer';
    }

    protected function _getCollection()
    {
        $customers = Mage::getResourceModel('customer/customer_collection')
            ->addAttributeToSelect('firstname')
            ->addAttributeToSelect('lastname');
        //$customers->setPageSize(1000);

        return $customers;
    }

    /**
     * @param $customer Mage_Customer_Model_Customer
     * @return \Elastica\Document
     */
    protected function _prepareDocument($customer)
    {
        $data = array(
            'id'            => $customer->getId(),
            'email'         => $customer->getData('email'),
            'firstname'     => $customer->getData('firstname'),
            'lastname'      => $customer->getData('lastname'),
            'fullname'      => $customer->getData('firstname') . ' ' . $customer->getData('lastname'),
        );

        $document = new \Elastica\Document($customer->getId(), $data);
        return $document;
    }

    /**
     * todokj try/catch so that if elastic search isn't runnin it will log an exception
     * but not block checkout
     * @param $customer Mage_Customer_Model_Customer
     */
    public function updateDocument($customer)
    {
        try {
            $document = $this->_prepareDocument($customer);
            $this->_getIndexType()->updateDocument($document);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }

    /**
     * @param $customer Mage_Customer_Model_Customer
     */
    public function addDocument($customer)
    {
        try {
            $document = $this->_prepareDocument($customer);
            $this->_getIndexType()->addDocument($document);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }
}
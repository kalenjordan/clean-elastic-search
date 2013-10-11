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
}
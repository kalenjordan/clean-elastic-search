<?php

class Clean_ElasticSearch_Model_Resource_Iterator_Batched extends Varien_Object
{
    const DEFAULT_BATCH_SIZE = 250;

    public function

    /**
     * This allows you to walk through a large collection and perform
     * operations on individual records.
     *
     * Here's how you can use it.
     *
     * Mage::getSingleton('stcore/resource_iterator_batched')->walk(
     *     $collection,
     *     array($this, 'batchIndividual'),
     *     array($this, 'batchAfter'),
     *     self::BATCH_SIZE
     * );
     *
     * @param $collection Varien_Data_Collection_Db
     * @param array $callback
     */
    public function walk($collection, array $individualCallbacks, $callbackAfterBatch = null, $batchSize = null)
    {
        if (!$batchSize) {
            $batchSize = self::DEFAULT_BATCH_SIZE;
        }

        $this->_beforeCollectionCount($collection);

        $collection->setPageSize($batchSize);
        $currentPage = 1;
        $pages = $collection->getLastPageNumber();

        $this->_afterCollectionCount($collection);

        do {
            $collection->setCurPage($currentPage);
            $collection->load();
            foreach ($collection as $item) {
                foreach ($individualCallbacks as $individualCallback) {
                    call_user_func($individualCallback, $item);
                }
            }

            if (is_array($callbackAfterBatch)) {
                call_user_func($callbackAfterBatch);
            }

            $currentPage++;
            $collection->clear();
        } while ($currentPage <= $pages);
    }

    /**
     * This removes the GROUP clause from the query, so that for example
     * if you're joining order_items to the order table and grouping on
     * entity_id, it removes that group before the getSelectCountSql()
     * is called, otherwise the result will be meaningless.
     *
     * This is a bit of a hack because if the query relies on
     * the GROUP for a proper count, then the count will be broken.
     *
     * @param $collection Varien_Data_Collection_Db
     */
    protected function _beforeCollectionCount($collection)
    {
        $group = $collection->getSelect()->getPart('group');

        if (!empty($group)) {
            $this->_group = $group;
            $collection->getSelect()->reset('group');
        }

        return $this;
    }

    /**
     * @param $collection Varien_Data_Collection_Db
     */
    protected function _afterCollectionCount($collection)
    {
        if (isset($this->_group)) {
            $collection->getSelect()->setPart('group', $this->_group);
        }

        return $this;
    }
}
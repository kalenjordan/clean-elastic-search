<?php

class Clean_ElasticSearch_Model_IndexType_Config extends Clean_ElasticSearch_Model_IndexType_Abstract
{
    protected function _getIndexTypeCode()
    {
        return 'config';
    }

    protected function _getCollection()
    {
        $configFields = Mage::getSingleton('adminhtml/config');
        $sections = $configFields->getSections()->asArray();

        $fields = array();
        foreach ($sections as $sectionCode => $section) {
            if (isset($section['groups'])) {
                foreach ($section['groups'] as $group) {
                    if (isset($group['fields'])) {
                        foreach ($group['fields'] as $field) {
                            $fields[] = array(
                                'section'       => $section['label'],
                                'section_code'  => $sectionCode,
                                'group'         => $group['label'],
                                'field'         => $field['label'],
                            );
                        }
                    }
                }
            }
        }

        return $fields;
    }

    protected function _prepareDocument($field)
    {
        $data = array(
            'group'         => $field['group'],
            'section'       => $field['section'],
            'section_code'  => $field['section_code'],
            'field'         => $field['field'],
        );

        $document = new \Elastica\Document('', $data);
        return $document;
    }
}
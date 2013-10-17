Elastic Search for Magento
==========================

Speed up that slow global admin search.

### Before

![Before](https://raw.github.com/kalenjordan/clean-elastic-search/master/example/before.png)

### After

![After](https://raw.github.com/kalenjordan/clean-elastic-search/master/example/after.png)


## Installation via composer

### 1. Install elasticsearch

Pretty easy to install, if you already have java installed, as I did in my local.

    wget https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-0.90.5.zip
    unzip elasticsearch-0.90.5.zip
    elasticsearch-0.90.5/bin/elasticsearch -f

Boom - it's installed and running now.

### 2. Install elastic search module

    "require": {
        "kalenjordan/elastic-search": "dev-master",
        "ruflin/elastica": "dev-master"
    }

### 3. Speed up the onKeyPress timeout

This will speed up the rate at which the autocomplete fires.

    # app/design/adminhtml/default/default/template/page/header.phtml

    new Ajax.Autocompleter(
        'global_search',
        'global_search_autocomplete',
        '<?php echo $this->getUrl('adminhtml/index/globalSearch') ?>',
        {
            paramName:"query",
            minChars:2,
            indicator:"global_search_indicator",
            updateElement:getSelectionId,
            evalJSON:'force',
            **frequency: 0.01**
        }
    );

## To Do

This is just in early development.   

 - Might need a more bulletproof authentication scheme, but bootstrapping Magento isn't an option
   for performance reasons.

 - Some people will probably want support for URL keys in the autocomplete results

 - Use bulk API to insert documents when doing reindexAll()


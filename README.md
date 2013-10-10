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

## To Do

This is just in early development.   

 - Implement iterative re-index, only reindexAll() works right now
 - Some people will probably want support for URL keys in the autocomplete results


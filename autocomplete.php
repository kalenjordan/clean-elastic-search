<?php

require_once('vendor/autoload.php');

// todokj this should be configurable
$elasticaClient = new \Elastica\Client(array(
    'host' => 'localhost',
    'port' => 9200
));
$elasticaIndex = $elasticaClient->getIndex('magento');

$query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
if (!$query) {
    die("Missing ?query");
}

$elasticaQueryString  = new \Elastica\Query\Prefix();
$elasticaQueryString->setPrefix('firstname', $query);
//$elasticaQueryString->setDefaultOperator('AND');
//$elasticaQueryString->setQuery($query);

$elasticaQuery = new \Elastica\Query();
$elasticaQuery->setQuery($elasticaQueryString);

$elasticaResultSet = $elasticaIndex->search($elasticaQuery);
$elasticaResults = $elasticaResultSet->getResults();
$totalResults = $elasticaResultSet->getTotalHits();

?>

<ul>
    <?php if (! $totalResults): ?>
        <li id="error" url="#">
            <div style="float:right; color:red; font-weight:bold;">[Error]</div>
            <strong>No results</strong><br/>
            <span class="informal">No results found for <em><?php echo $query; ?></em></span>
        </li>
    <?php endif; ?>
    <?php foreach ($elasticaResults as $elasticaResult): $data = $elasticaResult->getData(); ?>
        <li id="customer/1/10398" url="http://local.cleanprogram.com/index.php/cleanmagento/customer/edit/id/<?php echo $data['id']; ?>/">
            <div style="float:right; color:red; font-weight:bold;">[Customer]</div>
            <strong><?php echo $data['firstname']; ?> <?php echo $data['lastname']; ?></strong><br/>
            <span class="informal"></span>
        </li>
    <?php endforeach; ?>
</ul>

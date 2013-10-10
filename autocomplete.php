<?php

require_once('vendor/autoload.php');

function accessDenied()
{
    echo 'Access denied';
    exit;
}

/**
 * @param $result \Elastica\Result
 */
function getResultUrl($result)
{
    $baseUrl = $_GET['base_url'];
    $type = $result->getType();

    if ($type == 'customer') {
        return $baseUrl . 'customer/edit/id/' . $result->getId();
    } elseif ($type == 'order') {
        return $baseUrl . 'sales_order/view/order_id/' . $result->getId();
    }

    throw new Exception("Can't determine the type of this result");
}

/**
 * Need to do this so we can restrict access to logged-in admin users.
 */
session_save_path('var/session');
session_name('adminhtml');
session_start();

if (! isset($_SESSION['admin']['user'])) {
    accessDenied();
}

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

$elasticaQueryString  = new \Elastica\Query\MultiMatch();
$elasticaQueryString->setFields(array('firstname', 'lastname', 'fullname', 'email', 'increment_id'));
$elasticaQueryString->setQuery($query);
$elasticaQueryString->setParam('type', 'phrase_prefix');

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
        <li id="customer/1/10398" url="<?php echo getResultUrl($elasticaResult); ?>/">
            <div style="float:right; color:red; font-weight:bold;">[
                <?php echo $elasticaResult->getType(); ?>
            ]</div>
            <strong><?php echo $data['firstname']; ?> <?php echo $data['lastname']; ?></strong><br/>
            <span class="informal">
                <?php if ($elasticaResult->getType() == 'customer'): ?>
                    <?php echo $data['email']; ?>
                <?php elseif ($elasticaResult->getType() == 'order'): ?>
                    <?php echo $data['increment_id']; ?> - <?php echo $data['sku_list']; ?>
                <?php endif; ?>
            </span>
        </li>
    <?php endforeach; ?>
</ul>

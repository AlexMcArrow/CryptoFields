<?php

/**
 * @copyright Alex McArrow 2011
 * @author Alex McArrow
 * @package CryptoFields
 * @name index
 */
include 'cruptofields.php';

$CF = new CryptoFields ('test', CF_HARD, CF_CRC);
$CF->addkey ('form1');
$CF->addkeylist (array ('field1', 'field2', 'field3'));

echo '<hr><pre>';
print_r ($CF->getkeylist ());
if (isset ($_REQUEST['go'])) {
    print_r ($CF->getvaluelist ());
    echo '<hr>';
    print_r ($_REQUEST);
}
echo '<hr>';
print_r ($CF);
?>
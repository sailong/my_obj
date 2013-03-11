<?php
include_once(dirname(dirname(dirname(__FILE__))) . '/Daemon.inc.php');

$result = Gearman::send('del_photo_entity', '/attachment/1212121', PRIORITY_LOW);

?>
<?php

require_once 'vendor/autoload.php';

$m = new \Bobo1212\SharedMemory\Memory(1);
var_dump(1);
$m->lock();
$m->unLock();
var_dump(2);
sleep(60);
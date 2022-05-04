<?php

use fmihel\config\Config;

require __DIR__.'/../../source/ConfigCore.php';
require __DIR__.'/../../source/Config.php';


Config::test('./config.template2.php');

echo 'ok';
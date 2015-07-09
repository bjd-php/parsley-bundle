<?php

if (!is_file($autoloadFile = sprintf('%s/../vendor/autoload.php', __DIR__))) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

require $autoloadFile;

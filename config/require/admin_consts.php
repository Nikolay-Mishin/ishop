<?php

use ishop\App;

$path = PATH;
$adminpath = ADMIN;

return App::getConsts(compact('path', 'adminpath'));

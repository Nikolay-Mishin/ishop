<?php
// конфигурации БД - ORM RedBeanPHP

$base = "css";

return [
    'lib' => [
        "$base/bootstrap",
        "adminlte/bower_components/font-awesome/css/font-awesome.min",
        "megamenu/css/ionicons.min",
        "megamenu/css/style",
        "$base/flexslider", // screen
        //"memenu.css"
    ],
    'main' => [
        "$base/style",
        "$base/custom",
    ]
];

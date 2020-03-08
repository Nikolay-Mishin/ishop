<?php
// конфигурации БД - ORM RedBeanPHP

$base = "js";

return [
    'lib' => [
        "$base/jquery-1.11.0.min",
        "$base/bootstrap.min",
        "$base/validator",
        "$base/typeahead.bundle",
        "$base/imagezoom",
        "megamenu/js/megamenu",
        "$base/jquery.easydropdown",
        "$base/accordion",
        "$base/responsiveslides.min",
        "$base/slider",
        "$base/jquery.flexslider",
        "$base/flexslider", // defer
        "adminlte/bower_components/ckeditor/ckeditor",
        "adminlte/bower_components/ckeditor/adapters/jquery"
    ],
    'init' => [
        "@ " . CONF . "/scripts_consts.php",
        "$base/_variables",
        "$base/_base_functions",
        "$base/_functions"
    ],
    'main' => [
        "$base/editor",
        "$base/main"
    ]
];

<?php

$base = "/js";

return [
    'lib' => [
        "bower_components/jquery/dist/jquery.min",
        "$base/ajaxupload",
        "bower_components/bootstrap/dist/js/bootstrap.min",
        "bower_components/select2/dist/js/select2.full",
        "$base/validator",
        "dist/js/adminlte.min",
        "bower_components/ckeditor/ckeditor",
        "bower_components/ckeditor/adapters/jquery"
    ],
    'init' => [
        '@ ' . CONF . '/admin_scripts_consts.php',
        "_variables",
        "$base/_base_functions",
        "_functions"
    ],
    'main' => [
        "$base/editor",
        "my"
    ]
];

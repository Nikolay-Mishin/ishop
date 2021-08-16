<?php

return [
    'lib' => [
        "bower_components/jquery/dist/jquery.min",
        "/jquery-ui-1.12.1/jquery-ui.min",
        "/js/ajaxupload",
        "bower_components/bootstrap/dist/js/bootstrap.min",
        "bower_components/select2/dist/js/select2.full",
        "/js/validator",
        "bower_components/ckeditor/ckeditor",
        "bower_components/ckeditor/adapters/jquery",
    ],
    'init' => [
        "/js/_core",
        "@".CONF."/require/admin_consts.php",
        "_functions",
        "_variables",
    ],
    'main' => [
        "dist/js/adminlte.min",
        "/js/editor",
        "my",
    ]
];

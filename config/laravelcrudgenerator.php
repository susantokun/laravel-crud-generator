<?php

return [

    'custom_template'       => true,
    'path'                  => base_path('resources/laravel-crud-generator/'),
    'view_columns_number'   => 3,
    'custom_delimiter'      => ['%%', '%%'],
    'dynamic_view_template' => [
        'index' => ['formHeadingHtml', 'formBodyHtml', 'crudName', 'modelTitleSingular', 'modelTitlePlural', 'crudNameCap', 'modelName', 'viewName', 'routeGroup', 'primaryKey'],
        'form' => ['formFieldsHtml'],
        'create' => ['crudName', 'crudNameCap', 'modelName', 'modelNameCap', 'modelTitleSingular', 'viewName', 'routeGroup', 'viewTemplateDir'],
        'edit' => ['crudName', 'crudNameSingular', 'crudNameCap', 'modelNameCap', 'modelTitleSingular', 'modelName', 'viewName', 'routeGroup', 'primaryKey', 'viewTemplateDir'],
        'show' => ['formHeadingHtml', 'formBodyHtml', 'formBodyHtmlForShowView',  'modelTitleSingular', 'crudName', 'crudNameSingular', 'crudNameCap', 'modelName', 'viewName', 'routeGroup', 'primaryKey'],
        /*
         * Add new stubs templates here if you need to, like action, datatable...
         * custom_template needs to be activated for this to work
         */
    ]
];

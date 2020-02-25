<?php

namespace Susantokun\LaravelCrudGenerator\Commands;

use Illuminate\Console\Command;
use File;
use Illuminate\Support\Str;

class CrudCommand extends Command
{
    protected $signature = 'crud:generate
                            {name : the name of class}
                            {--namespace-controller= : namespace-controller}
                            {--namespace-model= : namespace-model}
                            {--namespace-resource= : namespace-resource}
                            {--name-model= : name-model}
                            {--fields= : Field names for the form & migration.}
                            {--fields_from_file= : fields_from_file}
                            {--validations= : validations}
                            {--data-resources= : data-resources}
                            {--indexes= : indexes}
                            {--pagination=10 : pagination}
                            {--pk=id : pk}
                            {--foreign-keys= : foreign-keys}
                            {--relationships= : relationships}
                            {--route=yes : route}
                            {--route-group= : route-group}
                            {--view-path= : view-path}
                            {--form-helper=html : form-helper}
                            {--localize=yes : Allow to localize? yes|no.}
                            {--locales=en : Locales language type.}
                            {--soft-deletes=no : soft-deletes}';

    protected $description = 'Generate CRUD including controller, model and migration.';
    protected $routeName = '';
    protected $controller = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $migrationName = Str::plural(Str::snake($name));
        $tableName = $migrationName;
        $perPage = intval($this->option('pagination'));

        $namespaceController = ($this->option('namespace-controller')) ? $this->option('namespace-controller') . '\\' : '';
        $namespaceModel = $this->option('namespace-model');

        $routeGroup = $this->option('route-group');
        $this->routeName = ($routeGroup) ? $routeGroup . '/' . Str::plural(Str::snake($name, '-')) : Str::plural(Str::snake($name, '-'));

        // auto generate fields
        $fields = rtrim($this->option('fields'), ';');
        if ($this->option('fields_from_file')) {
            $fields = $this->processJSONFields($this->option('fields_from_file'));
        }

        $primaryKey = $this->option('pk');
        $viewPath = $this->option('view-path');
        $foreignKeys = $this->option('foreign-keys');

        if ($this->option('fields_from_file')) {
            $foreignKeys = $this->processJSONForeignKeys($this->option('fields_from_file'));
        }

        $validations = trim($this->option('validations'));
        if ($this->option('fields_from_file')) {
            $validations = $this->processJSONValidations($this->option('fields_from_file'));
        }

        $dataResource = trim($this->option('data-resources'));
        if ($this->option('fields_from_file')) {
            $dataResource = $this->processJSONDataResources($this->option('fields_from_file'));
        }

        $fieldsArray = explode(';', $fields);
        $fillableArray = [];
        $migrationFields = '';

        foreach ($fieldsArray as $item) {
            $spareParts = explode('#', trim($item));
            $fillableArray[] = $spareParts[0];
            $modifier = !empty($spareParts[2]) ? $spareParts[2] : 'nullable';

            $migrationFields .= $spareParts[0] . '#' . $spareParts[1];
            $migrationFields .= '#' . $modifier;
            $migrationFields .= ';';
        }

        $commaSeparetedString = implode("', '", $fillableArray);
        $fillable = "['" . $commaSeparetedString . "']";

        $localize = $this->option('localize');
        $locales = $this->option('locales');

        $indexes = $this->option('indexes');
        $relationships = $this->option('relationships');
        if ($this->option('fields_from_file')) {
            $relationships = $this->processJSONRelationships($this->option('fields_from_file'));
        }
        $formHelper = $this->option('form-helper');
        $softDeletes = $this->option('soft-deletes');

        $this->call('crud:controller', ['name' => $namespaceController . $name . 'Controller', '--name-model' => $name, '--namespace-model' => $namespaceModel, '--data-validations' => $validations, '--fields' => $fields, '--view-path' => $viewPath, '--route-group' => $routeGroup, '--pagination' => $perPage]);
        $this->call('crud:model', ['name' => $name, '--namespace-model' => $namespaceModel, '--fillable' => $fillable, '--table' => $tableName, '--pk' => $primaryKey, '--relationships' => $relationships, '--soft-deletes' => $softDeletes]);
        $this->call('crud:migration', ['name' => $migrationName, '--schema' => $migrationFields, '--pk' => $primaryKey, '--indexes' => $indexes, '--foreign-keys' => $foreignKeys, '--soft-deletes' => $softDeletes]);
        $this->call('crud:view', ['name' => $name, '--fields' => $fields, '--validations' => $validations, '--view-path' => $viewPath, '--route-group' => $routeGroup, '--localize' => $localize, '--pk' => $primaryKey, '--form-helper' => $formHelper]);

        if ($localize == 'yes') {
            $this->call('crud:lang', ['name' => $name, '--fields' => $fields, '--locales' => $locales]);
        }

        // update route api
        $routeFile = base_path('routes/web.php');

        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $this->nameController = ($namespaceController != '') ? $namespaceController . '\\' . $name . 'Controller' : $name . 'Controller';

            $isAdded = File::append($routeFile, "\n" . implode("\n", $this->addRoutes()));

            if ($isAdded) {
                $this->info('Route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
    }

    protected function addRoutes()
    {
        return ["Route::resource('" . $this->routeName . "', '" . $this->nameController . "');\nRoute::post('" . $this->routeName . "/delete/{id}', '" . $this->nameController . "@delete');"];
    }

    protected function processJSONFields($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        $fieldsString = '';
        foreach ($fields->fields as $field) {
            if ($field->type === 'select' || $field->type === 'enum') {
                $fieldsString .= $field->name . '#' . $field->type . '#options=' . json_encode($field->options) . ';';
            } else {
                $fieldsString .= $field->name . '#' . $field->type . ';';
            }
        }

        $fieldsString = rtrim($fieldsString, ';');

        return $fieldsString;
    }

    protected function processJSONForeignKeys($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'foreign_keys')) {
            return '';
        }

        $foreignKeysString = '';
        foreach ($fields->foreign_keys as $foreign_key) {
            $foreignKeysString .= $foreign_key->column . '#' . $foreign_key->references . '#' . $foreign_key->on;

            if (property_exists($foreign_key, 'onDelete')) {
                $foreignKeysString .= '#' . $foreign_key->onDelete;
            }

            if (property_exists($foreign_key, 'onUpdate')) {
                $foreignKeysString .= '#' . $foreign_key->onUpdate;
            }

            $foreignKeysString .= ',';
        }

        $foreignKeysString = rtrim($foreignKeysString, ',');

        return $foreignKeysString;
    }

    protected function processJSONRelationships($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'relationships')) {
            return '';
        }

        $relationsString = '';
        foreach ($fields->relationships as $relation) {
            $relationsString .= $relation->name . '#' . $relation->type . '#' . $relation->class . ';';
        }

        $relationsString = rtrim($relationsString, ';');

        return $relationsString;
    }

    protected function processJSONValidations($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'validations')) {
            return '';
        }

        $validationsString = '';
        foreach ($fields->validations as $validation) {
            $validationsString .= $validation->field . '#' . $validation->rules . ';';
        }

        $validationsString = rtrim($validationsString, ';');

        return $validationsString;
    }

    protected function processJSONDataResources($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'fields')) {
            return '';
        }

        $validationsString = '';
        foreach ($fields->validations as $validation) {
            $validationsString .= $validation->field . '#' . $validation->rules . ';';
        }

        $validationsString = rtrim($validationsString, ';');

        return $validationsString;
    }
}

<?php

namespace Susantokun\LaravelCrudGenerator\Commands;

use Illuminate\Console\Command;
use File;
use Illuminate\Support\Str;

class CrudApiCommand extends Command
{
    protected $signature = 'crud:api
                            {name : the name of class api controller}
                            {--fields= : Field names for the form & migration.}
                            {--fields_from_file= : fields_from_file}
                            {--validations= : validations}
                            {--namespace-controller= : namespace-controller}
                            {--namespace-model= : namespace-model}
                            {--namespace-resource= : namespace-resource}
                            {--name-model= : name-model}
                            {--name-resource= : name-resource}
                            {--pk=id : pk}
                            {--pagination=25 : pagination}
                            {--indexes= : indexes}
                            {--foreign-keys= : foreign-keys}
                            {--relationships= : relationships}
                            {--route=yes : route}
                            {--route-group= : route-group}
                            {--data-resources= : data-resources}
                            {--soft-deletes=no : soft-deletes}
                            ';

    protected $description = 'Generate CRUD for API including controller, model and resource.';
    protected $routeName = '';
    protected $controller = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');

        $namespaceController = ($this->option('namespace-controller')) ? $this->option('namespace-controller') . '\\' : '';
        $namespaceModel = $this->option('namespace-model');
        $namespaceResource = $this->option('namespace-resource');
        $migrationName = Str::plural(Str::snake($name));
        $tableName = $migrationName;
        $routeGroup = strtolower($this->option('route-group'));
        $this->routeName = ($routeGroup) ? $routeGroup . '/' . Str::plural(Str::snake($name, '-')) : Str::plural(Str::snake($name, '-'));
        $perPage = intval($this->option('pagination'));

        // $controllerNamespace = ($this->option('controller-namespace')) ? $this->option('controller-namespace') . '\\' : '';
        // $modelNamespace = ($this->option('model-namespace')) ? trim($this->option('model-namespace')) . '\\' : '';

        $fields = rtrim($this->option('fields'), ';');
        // auto generate fields
        if ($this->option('fields_from_file')) {
            $fields = $this->processJSONFields($this->option('fields_from_file'));
        }

        $primaryKey = $this->option('pk');

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
            // Process migration fields
            $migrationFields .= $spareParts[0] . '#' . $spareParts[1];
            $migrationFields .= '#' . $modifier;
            $migrationFields .= ';';
        }

        $commaSeparetedString = implode("', '", $fillableArray);
        $fillable = "['" . $commaSeparetedString . "']";

        $indexes = $this->option('indexes');
        $relationships = $this->option('relationships');
        if ($this->option('fields_from_file')) {
            $relationships = $this->processJSONRelationships($this->option('fields_from_file'));
        }

        $softDeletes = $this->option('soft-deletes');

        $this->call('crud:api-controller', ['name' => $namespaceController .$name . 'Controller', '--name-model' => $name, '--namespace-model' => $namespaceModel, '--namespace-resource' => $namespaceResource, '--pagination' => $perPage, '--data-validations' => $validations]);
        $this->call('crud:migration', ['name' => $migrationName, '--schema' => $migrationFields, '--pk' => $primaryKey, '--indexes' => $indexes, '--foreign-keys' => $foreignKeys, '--soft-deletes' => $softDeletes]);
        $this->call('crud:api-resource', ['name' => $name . 'Resource', '--namespace-resource' => $namespaceResource, '--data-resources' => $dataResource]);
        $this->call('crud:model', ['name' => $name,  '--namespace-model' => $namespaceModel,'--fillable' => $fillable, '--table' => $tableName, '--pk' => $primaryKey, '--relationships' => $relationships, '--soft-deletes' => $softDeletes]);

        // update route api
        $routeFile = base_path('routes/api.php');
        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $this->nameController   = ($namespaceController != '') ? $namespaceController . '\\' . $name . 'Controller' : $name . 'Controller';

            $isAdded = File::append($routeFile, "\n" . implode("\n", $this->addRoutes()));

            if ($isAdded) {
                $this->info('Route API added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
    }

    protected function addRoutes()
    {
        return ["Route::resource('" . $this->routeName . "', '" . $this->nameController . "', ['except' => ['create', 'edit']]);"];
    }
    protected function processJSONFields($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        $fieldsString = '';
        foreach ($fields->fields as $field) {
            if ($field->type == 'select') {
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

        $dataFieldString = '';
        foreach ($fields->fields as $dataField) {
            $dataFieldString .= $dataField->name . '#' . $dataField->name . ';';
        }

        $dataFieldString = rtrim($dataFieldString, ';');

        return $dataFieldString;
    }

}

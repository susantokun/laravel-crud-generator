<?php

namespace Susantokun\LaravelCrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CrudApiControllerCommand extends GeneratorCommand
{
    protected $signature = 'crud:api-controller
                            {name : the name of api controller}
                            {--namespace-controller= : namespace-controller}
                            {--namespace-model= : namespace-mode}
                            {--namespace-resource= : namespace-resource}
                            {--name-model= : name-model}
                            {--name-resource= : name-resource}
                            {--data-validations= : data-validations}
                            {--pagination=25 : pagination}
                            {--force : replace file}';

    protected $description = 'Create a new api controller.';
    protected $type = 'API Controller';

    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/ApiController.stub'
            : __DIR__ . '/../stubs/ApiController.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . ($this->option('namespace-controller') ? $this->option('namespace-controller') : 'Http\Controllers');
    }

    protected function alreadyExists($rawName)
    {
        if ($this->option('force')) {
            return false;
        }
        return parent::alreadyExists($rawName);
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $namespaceModel      = $this->option('namespace-model');
        $namespaceResource   = $this->option('namespace-resource');


        $nameModel        = $this->option('name-model');
        $nameResource     = $nameModel . 'Resource';
        $nameCrudPlural   = Str::plural(Str::camel($nameModel));
        $nameCrudSingular = Str::singular(Str::camel($nameModel));


        $validations = rtrim($this->option('data-validations'), ';');
        $dataValidations = '';
        if (trim($validations) != '') {
            $dataValidations = "\$this->validate(\$request, [";

            $rules = explode(';', $validations);
            foreach ($rules as $v) {
                if (trim($v) == '') {
                    continue;
                }
                $parts = explode('#', $v);
                $fieldName = trim($parts[0]);
                $rules = trim($parts[1]);
                $dataValidations .= "\n\t\t\t'$fieldName' => '$rules',";
            }

            $dataValidations = substr($dataValidations, 0, -1);
            $dataValidations .= "\n\t\t]);";
        }

        return $this->replaceNamespace($stub, $name)
            ->replaceNamespaceModel($stub, $namespaceModel)
            ->replaceNamespaceResource($stub, $namespaceResource)
            ->replaceNameModel($stub, $nameModel)
            ->replaceNameResource($stub, $nameResource)
            ->replaceNameCrudPlural($stub, $nameCrudPlural)
            ->replaceNameCrudSingular($stub, $nameCrudSingular)
            ->replaceDataValidations($stub, $dataValidations)

            ->replaceClass($stub, $name);
    }

    protected function replaceNamespaceModel(&$stub, $namespaceModel)
    {
        $stub = str_replace('{{namespaceModel}}', $namespaceModel, $stub);
        return $this;
    }
    protected function replacenamespaceResource(&$stub, $namespaceResource)
    {
        $stub = str_replace('{{namespaceResource}}', $namespaceResource, $stub);
        return $this;
    }
    protected function replaceNameModel(&$stub, $nameModel)
    {
        $stub = str_replace('{{nameModel}}', $nameModel, $stub);
        return $this;
    }
    protected function replaceNameResource(&$stub, $nameResource)
    {
        $stub = str_replace('{{nameResource}}', $nameResource, $stub);
        return $this;
    }

    protected function replaceNameCrudPlural(&$stub, $nameCrudPlural)
    {
        $stub = str_replace('{{nameCrudPlural}}', $nameCrudPlural, $stub);
        return $this;
    }
    protected function replaceNameCrudSingular(&$stub, $nameCrudSingular)
    {
        $stub = str_replace('{{nameCrudSingular}}', $nameCrudSingular, $stub);
        return $this;
    }
    protected function replaceDataValidations(&$stub, $dataValidations)
    {
        $stub = str_replace('{{dataValidations}}', $dataValidations, $stub);

        return $this;
    }
}

<?php

namespace Susantokun\LaravelCrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudApiResourceCommand extends GeneratorCommand
{
    protected $signature = 'crud:api-resource
                            {name : the name of resource}
                            {--namespace-resource= : namespace-resource}
                            {--data-resources= : data-resources}
                            {--force : replace file}';

    protected $description = 'Create a new api resource.';
    protected $type = 'API Resource';

    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/ApiResource.stub'
            : __DIR__ . '/../stubs/ApiResource.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . ($this->option('namespace-resource') ? $this->option('namespace-resource') : 'Resources');
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

        $namespaceResource = $this->option('namespace-resource');

        $resource = rtrim($this->option('data-resources'), ';');
        $dataResource = '';
        if (trim($resource) != '') {
            $dataResource = "return [";

            $rules = explode(';', $resource);
            foreach ($rules as $v) {
                if (trim($v) == '') {
                    continue;
                }
                $parts = explode('#', $v);
                $fieldName = trim($parts[0]);
                $rules = trim($parts[1]);
                $dataResource .= "\n\t\t\t'$fieldName' => \$this->$fieldName,";
            }

            $dataResource = substr($dataResource, 0, -1);
            $dataResource .= "\n\t\t];";
        }

        return $this->replaceNamespace($stub, $name)
            ->replaceNamespaceResource($stub, $namespaceResource)
            ->replaceDataResource($stub, $dataResource)
            ->replaceClass($stub, $name);
    }

    protected function replaceNamespaceResource(&$stub, $namespaceResource)
    {
        $stub = str_replace('{{namespaceResource}}', $namespaceResource, $stub);
        return $this;
    }
    protected function replaceDataResource(&$stub, $dataResource)
    {
        $stub = str_replace('{{dataResource}}', $dataResource, $stub);
        return $this;
    }
}

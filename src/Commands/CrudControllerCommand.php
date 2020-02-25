<?php

namespace Susantokun\LaravelCrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CrudControllerCommand extends GeneratorCommand
{
    protected $signature = 'crud:controller
                            {name : the name of controller}
                            {--namespace-controller= : namespace-controller}
                            {--namespace-model= : namespace-mode}
                            {--namespace-resource= : namespace-resource}
                            {--name-model= : name-model}
                            {--data-validations= : data-validations}
                            {--pagination=10 : pagination}
                            {--view-path= : view-path}
                            {--route-group= : route-group}
                            {--fields= : fields}
                            {--force : replace file}';

    protected $description = 'Create a new controller.';
    protected $type = 'Controller';

    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/Controller.stub'
            : __DIR__ . '/../stubs/Controller.stub';
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
        $nameCrudPlural   = Str::plural(Str::camel($nameModel));
        $nameCrudSingular = Str::singular(Str::camel($nameModel));

        $perPage = intval($this->option('pagination'));
        $viewPath = $this->option('view-path') ? $this->option('view-path') . '.' : '';
        $modelTitleSingular = Str::title(Str::snake($this->option('name-model'), ' '));
        $modelTitlePlural = Str::plural(Str::title(Str::snake($this->option('name-model'), ' ')));
        $routeGroup = ($this->option('route-group')) ? $this->option('route-group') . '/' : '';
        $routePrefix = ($this->option('route-group')) ? $this->option('route-group') : '';
        $routePrefixCap = ucfirst($routePrefix);
        $viewName = Str::plural(Str::snake($this->option('name-model'), '-'));
        $fields = $this->option('fields');

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

        $snippet = <<<EOD
if (\$request->hasFile('{{fieldName}}')) {
            \$requestData['{{fieldName}}'] = \$request->file('{{fieldName}}')
            ->store('uploads', 'public');
        }
EOD;

        $fieldsArray = explode(';', $fields);
        $fileSnippet = '';
        $whereSnippet = '';

        if ($fields) {
            $x = 0;
            foreach ($fieldsArray as $index => $item) {
                $itemArray = explode('#', $item);

                if (trim($itemArray[1]) == 'file') {
                    $fileSnippet .= str_replace('{{fieldName}}', trim($itemArray[0]), $snippet) . "\n";
                }

                $fieldName = trim($itemArray[0]);

                $whereSnippet .= ($index == 0) ? "where('$fieldName', 'LIKE', \"%\$keyword%\")" . "\n                " : "->orWhere('$fieldName', 'LIKE', \"%\$keyword%\")" . "\n                ";
            }

            $whereSnippet .= "->";
        }

        return $this->replaceNamespace($stub, $name)
            ->replaceNamespaceModel($stub, $namespaceModel)
            ->replaceNamespaceResource($stub, $namespaceResource)
            ->replaceNameModel($stub, $nameModel)
            ->replaceNameCrudPlural($stub, $nameCrudPlural)
            ->replaceNameCrudSingular($stub, $nameCrudSingular)
            ->replaceDataValidations($stub, $dataValidations)

            ->replacePaginationNumber($stub, $perPage)
            ->replaceViewName($stub, $viewName)
            ->replaceViewPath($stub, $viewPath)
            ->replaceModelTitleSingular($stub, $modelTitleSingular)
            ->replaceModelTitlePlural($stub, $modelTitlePlural)
            ->replaceRoutePrefix($stub, $routePrefix)
            ->replaceRoutePrefixCap($stub, $routePrefixCap)
            ->replaceRouteGroup($stub, $routeGroup)
            ->replaceFileSnippet($stub, $fileSnippet)
            ->replaceWhereSnippet($stub, $whereSnippet)

            ->replaceClass($stub, $name);
    }

    protected function replacePaginationNumber(&$stub, $perPage)
    {
        $stub = str_replace('{{pagination}}', $perPage, $stub);

        return $this;
    }
    protected function replaceViewName(&$stub, $viewName)
    {
        $stub = str_replace('{{viewName}}', $viewName, $stub);

        return $this;
    }
    protected function replaceViewPath(&$stub, $viewPath)
    {
        $stub = str_replace('{{viewPath}}', $viewPath, $stub);

        return $this;
    }
    protected function replaceModelTitleSingular(&$stub, $modelTitleSingular)
    {
        $stub = str_replace('{{modelTitleSingular}}', $modelTitleSingular, $stub);

        return $this;
    }
    protected function replaceModelTitlePlural(&$stub, $modelTitlePlural)
    {
        $stub = str_replace('{{modelTitlePlural}}', $modelTitlePlural, $stub);

        return $this;
    }
    protected function replaceRoutePrefix(&$stub, $routePrefix)
    {
        $stub = str_replace('{{routePrefix}}', $routePrefix, $stub);

        return $this;
    }
    protected function replaceRoutePrefixCap(&$stub, $routePrefixCap)
    {
        $stub = str_replace('{{routePrefixCap}}', $routePrefixCap, $stub);

        return $this;
    }
    protected function replaceRouteGroup(&$stub, $routeGroup)
    {
        $stub = str_replace('{{routeGroup}}', $routeGroup, $stub);

        return $this;
    }
    protected function replaceFileSnippet(&$stub, $fileSnippet)
    {
        $stub = str_replace('{{fileSnippet}}', $fileSnippet, $stub);

        return $this;
    }
    protected function replaceWhereSnippet(&$stub, $whereSnippet)
    {
        $stub = str_replace('{{whereSnippet}}', $whereSnippet, $stub);

        return $this;
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

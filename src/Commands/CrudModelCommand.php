<?php

namespace Susantokun\LaravelCrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CrudModelCommand extends GeneratorCommand
{
    protected $signature = 'crud:model
                            {name : the name of model}
                            {--table= : table}
                            {--fillable= : fillable}
                            {--relationships= : relationships}
                            {--pk=id : The name of the primary key.}
                            {--soft-deletes=no : soft-deletes}
                            {--force : replace file}
                            {--namespace-model= : namespace-mode}
                            {--name-model= : name-model}';

    protected $description = 'Create a new model.';
    protected $type = 'Model';

    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/Model.stub'
            : __DIR__ . '/../stubs/Model.stub';
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

        $namespaceModel = $this->option('namespace-model');

        $table = $this->option('table') ?: Str::plural(Str::snake($this->argument('name')));
        $fillable = $this->option('fillable');
        $primaryKey = $this->option('pk');
        $relationships = trim($this->option('relationships')) != '' ? explode(';', trim($this->option('relationships'))) : [];
        $softDeletes = $this->option('soft-deletes');

        if (!empty($primaryKey)) {
            $primaryKey = <<<EOD
protected \$primaryKey = '$primaryKey';
EOD;
        }

        $ret = $this->replaceNamespace($stub, $name)
            ->replaceNamespaceModel($stub, $namespaceModel)
            ->replaceTable($stub, $table)
            ->replaceFillable($stub, $fillable)
            ->replacePrimaryKey($stub, $primaryKey)
            ->replaceSoftDelete($stub, $softDeletes);

        foreach ($relationships as $rel) {
            // relationshipname#relationshiptype#args_separated_by_pipes
            // e.g. employees#hasMany#App\Employee|id|dept_id
            // user is responsible for ensuring these relationships are valid
            $parts = explode('#', $rel);

            if (count($parts) != 3) {
                continue;
            }

            // blindly wrap each arg in single quotes
            $args = explode('|', trim($parts[2]));
            $argsString = '';
            foreach ($args as $k => $v) {
                if (trim($v) == '') {
                    continue;
                }

                $argsString .= trim($v) . ", ";
            }

            $argsString = substr($argsString, 0, -2); // remove last comma

            $ret->createRelationshipFunction($stub, trim($parts[0]), trim($parts[1]), $argsString);
        }

        $ret->replaceRelationshipPlaceholder($stub);

        return $ret->replaceClass($stub, $name);
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . ($this->option('namespace-model') ? $this->option('namespace-model') : 'Models');
    }

    protected function replaceNamespaceModel(&$stub, $namespaceModel)
    {
        $stub = str_replace('{{namespaceModel}}', $namespaceModel, $stub);
        return $this;
    }

    protected function replaceTable(&$stub, $table)
    {
        $stub = str_replace('{{table}}', $table, $stub);

        return $this;
    }
    protected function replaceFillable(&$stub, $fillable)
    {
        $stub = str_replace('{{fillable}}', $fillable, $stub);

        return $this;
    }
    protected function replacePrimaryKey(&$stub, $primaryKey)
    {
        $stub = str_replace('{{primaryKey}}', $primaryKey, $stub);

        return $this;
    }
    protected function replaceSoftDelete(&$stub, $replaceSoftDelete)
    {
        if ($replaceSoftDelete == 'yes') {
            $stub = str_replace('{{softDeletes}}', "use SoftDeletes;\n    ", $stub);
            $stub = str_replace('{{useSoftDeletes}}', "use Illuminate\Database\Eloquent\SoftDeletes;\n", $stub);
        } else {
            $stub = str_replace('{{softDeletes}}', '', $stub);
            $stub = str_replace('{{useSoftDeletes}}', '', $stub);
        }

        return $this;
    }
    protected function createRelationshipFunction(&$stub, $relationshipName, $relationshipType, $argsString)
    {
        $tabIndent = '    ';
        $code = "public function " . $relationshipName . "()\n" . $tabIndent . "{\n" . $tabIndent . $tabIndent
            . "return \$this->" . $relationshipType . "(" . $argsString . ");"
            . "\n" . $tabIndent . "}";

        $str = '{{relationships}}';
        $stub = str_replace($str, $code . "\n" . $tabIndent . $str, $stub);

        return $this;
    }
    protected function replaceRelationshipPlaceholder(&$stub)
    {
        $stub = str_replace('{{relationships}}', '', $stub);
        return $this;
    }

}

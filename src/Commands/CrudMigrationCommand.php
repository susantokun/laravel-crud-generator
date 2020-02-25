<?php

namespace Susantokun\LaravelCrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudMigrationCommand extends GeneratorCommand
{
    protected $signature = 'crud:migration
                            {name : the name of migration}
                            {--schema= : schema}
                            {--indexes= : indexes}
                            {--foreign-keys= : foreign-keys}
                            {--pk=id : pk}
                            {--soft-deletes=no : soft-deletes}';

    protected $description = 'Create a new migration.';
    protected $type = 'Migration';

    protected $typeLookup = [
        'char' => 'char',
        'date' => 'date',
        'datetime' => 'dateTime',
        'time' => 'time',
        'timestamp' => 'timestamp',
        'text' => 'text',
        'mediumtext' => 'mediumText',
        'longtext' => 'longText',
        'json' => 'json',
        'jsonb' => 'jsonb',
        'binary' => 'binary',
        'number' => 'integer',
        'integer' => 'integer',
        'unsignedbigint' => 'unsignedBigInteger',
        'bigint' => 'bigInteger',
        'mediumint' => 'mediumInteger',
        'tinyint' => 'tinyInteger',
        'smallint' => 'smallInteger',
        'boolean' => 'boolean',
        'decimal' => 'decimal',
        'double' => 'double',
        'float' => 'float',
        'enum' => 'enum',
    ];

    protected function getStub()
    {
        return config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . '/Migration.stub'
            : __DIR__ . '/../stubs/Migration.stub';
    }

    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);
        $datePrefix = date('Y_m_d_His');

        return database_path('/migrations/') . $datePrefix . '_create_' . $name . '_table.php';
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $tableName = $this->argument('name');
        $className = 'Create' . str_replace(' ', '', ucwords(str_replace('_', ' ', $tableName))) . 'Table';

        $fieldsToIndex = trim($this->option('indexes')) != '' ? explode(',', $this->option('indexes')) : [];
        $foreignKeys = trim($this->option('foreign-keys')) != '' ? explode(',', $this->option('foreign-keys')) : [];

        $schema = rtrim($this->option('schema'), ';');
        $fields = explode(';', $schema);

        $data = array();

        if ($schema) {
            $x = 0;
            foreach ($fields as $field) {
                $fieldArray = explode('#', $field);
                $data[$x]['name'] = trim($fieldArray[0]);
                $data[$x]['type'] = trim($fieldArray[1]);
                if (($data[$x]['type'] === 'select'
                        || $data[$x]['type'] === 'enum')
                    && isset($fieldArray[2])
                ) {
                    $options = trim($fieldArray[2]);
                    $data[$x]['options'] = str_replace('options=', '', $options);
                }

                $data[$x]['modifier'] = '';

                $modifierLookup = [
                    'comment',
                    'default',
                    'first',
                    'nullable',
                    'unsigned',
                ];

                if (isset($fieldArray[2]) && in_array(trim($fieldArray[2]), $modifierLookup)) {
                    $data[$x]['modifier'] = "->" . trim($fieldArray[2]) . "()";
                }

                $x++;
            }
        }

        $tabIndent = '    ';

        $schemaFields = '';
        foreach ($data as $item) {
            if (isset($this->typeLookup[$item['type']])) {
                $type = $this->typeLookup[$item['type']];

                if ($type === 'select' || $type === 'enum') {
                    $enumOptions = array_keys(json_decode($item['options'], true));
                    $enumOptionsStr = implode(",", array_map(function ($string) {
                        return '"' . $string . '"';
                    }, $enumOptions));
                    $schemaFields .= "\$table->" . $type . "('" . $item['name'] . "', [" . $enumOptionsStr . "])";
                } else {
                    $schemaFields .= "\$table->" . $type . "('" . $item['name'] . "')";
                }
            } else {
                $schemaFields .= "\$table->string('" . $item['name'] . "')";
            }

            // Append column modifier
            $schemaFields .= $item['modifier'];
            $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        // add indexes and unique indexes as necessary
        foreach ($fieldsToIndex as $fldData) {
            $line = trim($fldData);

            // is a unique index specified after the #?
            // if no hash present, we append one to make life easier
            if (strpos($line, '#') === false) {
                $line .= '#';
            }

            // parts[0] = field name (or names if pipe separated)
            // parts[1] = unique specified
            $parts = explode('#', $line);
            if (strpos($parts[0], '|') !== 0) {
                $fieldNames = "['" . implode("', '", explode('|', $parts[0])) . "']"; // wrap single quotes around each element
            } else {
                $fieldNames = trim($parts[0]);
            }

            if (count($parts) > 1 && $parts[1] == 'unique') {
                $schemaFields .= "\$table->unique(" . trim($fieldNames) . ")";
            } else {
                $schemaFields .= "\$table->index(" . trim($fieldNames) . ")";
            }

            $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        // foreign keys
        foreach ($foreignKeys as $fk) {
            $line = trim($fk);

            $parts = explode('#', $line);

            // if we don't have three parts, then the foreign key isn't defined properly
            // --foreign-keys="foreign_entity_id#id#foreign_entity#onDelete#onUpdate"
            if (count($parts) == 3) {
                $schemaFields .= "\$table->foreign('" . trim($parts[0]) . "')"
                    . "->references('" . trim($parts[1]) . "')->on('" . trim($parts[2]) . "')";
            } elseif (count($parts) == 4) {
                $schemaFields .= "\$table->foreign('" . trim($parts[0]) . "')"
                    . "->references('" . trim($parts[1]) . "')->on('" . trim($parts[2]) . "')"
                    . "->onDelete('" . trim($parts[3]) . "')" . "->onUpdate('" . trim($parts[3]) . "')";
            } elseif (count($parts) == 5) {
                $schemaFields .= "\$table->foreign('" . trim($parts[0]) . "')"
                    . "->references('" . trim($parts[1]) . "')->on('" . trim($parts[2]) . "')"
                    . "->onDelete('" . trim($parts[3]) . "')" . "->onUpdate('" . trim($parts[4]) . "')";
            } else {
                continue;
            }

            $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        $primaryKey = $this->option('pk');
        $softDeletes = $this->option('soft-deletes');

        $softDeletesSnippets = '';
        if ($softDeletes == 'yes') {
            $softDeletesSnippets = "\$table->softDeletes();\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        $schemaUp =
            "Schema::create('" . $tableName . "', function (Blueprint \$table) {
            \$table->bigIncrements('" . $primaryKey . "');
            "
            . $softDeletesSnippets .
            $schemaFields .
            // "\$table->enum('status', ['0', '1']);\n" . $tabIndent . $tabIndent . $tabIndent .
            "\$table->timestamps();\n" . $tabIndent . $tabIndent .
            "});";

        $schemaDown = "Schema::dropIfExists('" . $tableName . "');";

        return $this->replaceSchemaUp($stub, $schemaUp)
            ->replaceSchemaDown($stub, $schemaDown)
            ->replaceClass($stub, $className);
    }

    protected function replaceSchemaUp(&$stub, $schemaUp)
    {
        $stub = str_replace('{{schema_up}}', $schemaUp, $stub);

        return $this;
    }
    protected function replaceSchemaDown(&$stub, $schemaDown)
    {
        $stub = str_replace('{{schema_down}}', $schemaDown, $stub);

        return $this;
    }
}

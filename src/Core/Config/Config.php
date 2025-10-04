<?php

namespace Core\Config;

use Core\Contracts\Config\ConfigInterface;
use Core\Models\Data\DataObject;

/**
 * @method getSession()
 * @method getDatabase()
 * @method getCsrfEnabled()
 */
class Config implements ConfigInterface
{
    public const SESSION_STORAGE = 'storage';
    public const HEADERS = 'headers';

    private ?DataObject $data = null;
    private bool $readonly;
    private array $allowedChanges;

    public function __construct(bool $readonly = true, $allowedChanges = ['session'])
    {
        $data = include CONFIG_PATH . "/env.php";
        $defaultData = include CONFIG_PATH . '/env.schema.php';
        $data = array_replace_recursive($this->getDefaultConfig($defaultData['_schema']), $data);
        $this->data = new DataObject($data);
        $this->readonly = $readonly;
        $this->allowedChanges = array_map(fn ($prop) => strtolower($prop), $allowedChanges);
    }

    public function __call($name, $arguments)
    {
        if (str_starts_with($name, 'set')) {
            $block = $this->readonly;
            if ($block && count($this->allowedChanges)) {
                $property = substr($name, 3);

                if (in_array(strtolower($property), $this->allowedChanges)) {
                    $block = false;
                }
            }

            if ($block) {
                return null;
            }
        }

        return $this->data->$name($arguments);
    }

    protected function getDefaultConfig(array $schema = []): array
    {
        $defaults = [];

        foreach ($schema as $key => $value) {
            // A primitive value
            if (isset($value['default'])) {
                $defaults[$key] = $value['default'];
                continue;
            }

            // Object With Fields
            if (($value['type'] ?? null) === 'object' && isset($value['fields'])) {
                $defaults[$key] = $this->getDefaultConfig($value['fields']);
                continue;
            }

            // Recurse Deeper
            if ((is_array($value))) {
                $nestedData = $this->getDefaultConfig($value);
                if ($nestedData !== []) {
                    $defaults[$key] = $nestedData;
                }
            }
        }

        return $defaults;
    }
}

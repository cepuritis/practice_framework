<?php

namespace Core\Config;

use Core\Models\Data\DataObject;

/**
 * @method getSession()
 * @method getDatabase()
 */
class Config
{
    public const SESSION_STORAGE = 'storage';
    public const HEADERS = 'headers';

    private ?DataObject $data = null;
    private bool $readonly;
    private array $allowedChanges;

    public function __construct(bool $readonly = true, $allowedChanges = ['session'])
    {
        $data = include CONFIG_PATH . "/env.php";
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
}

<?php

namespace Core\Contracts\Tags;

use http\Exception\RuntimeException;

abstract class HtmlTag
{
    protected array $attributes = [];
    protected string $name;
    public function __construct(string $name, array $attributes = [])
    {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addAttribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeAttribute(string $name): self
    {
        if (!isset($this->attributes[$name])) {
            throw new \RuntimeException("Attribute {$name} does not exist on {$this->name} tag");
        }

        unset($this->attributes[$name]);

        return $this;
    }

    /**
     * @return void
     */
    public function render(bool $close = false, string $content = ""): void
    {
        if (!$this->name) {
            throw new RuntimeException("Attribute Name Not set");
        }

        ob_start();
        echo "<{$this->name} ";
        foreach ($this->attributes as $key => $value) {
            echo "{$key}";
            if ($value) {
                echo "=\"{$value}\" ";
            }
        }
        if (!$close) {
            echo "/>";
        } else {
            echo ">" .PHP_EOL;
            echo $content . "</{$this->name}>";
        }
        echo ob_get_clean();
    }
}

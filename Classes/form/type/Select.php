<?php

namespace form\type;

class Select extends Input {
    private array $options = [];

    public function __construct(
        string $value,
        bool $required,
        string $name,
        string $id,
        string $function = null,
        string $RadioValue = "",
        string $event = null,
        ?array $options = null
    ) {
        parent::__construct($value, $required, $name, $id, $function, $RadioValue, $event);
        $this->options = $options ?? [];
    }

    public function addOption(string $value, string $text) {
        $this->options[] = "<option value=\"{$value}\">{$text}</option>";
        return $this;
    }

    public function addOptionArray(string $value,array $options) {
        foreach($options as $option){
            $this->options[] = "<option value=\"{$value}\">{$option}</option>";
        }
        return $this;
    }

    public function render(): string {
        $function = $this->function === null ? "" : $this->event . '="' . $this->function . '"';
        $select = "<select id=\"{$this->id}\" name=\"{$this->name}\" {$function}>";
        foreach($this->options as $option){
            $select .= $option;
        }
        $select .= "</select>";

        if($this->label !== ""){
            return $this->label.$select;
        }
        return $select;
    }
}   
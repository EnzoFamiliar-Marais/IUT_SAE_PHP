<?php 

namespace form\type;

class File extends Input{

    protected string $type = "file";

    public function render(): string{

        $required = $this->required ? "required=false" : "";
        $value = $this->value === "" ? "" : "value=".$this->value;
        
        return "<input type=".$this->type." accept='image/*' $value id=".$this->id." name=".$this->name.">";
    }
}
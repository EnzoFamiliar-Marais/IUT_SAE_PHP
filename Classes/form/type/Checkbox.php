<?php 

namespace form\type;

class Checkbox extends Input{

    protected string $type = "checkbox";  
    
    public function render(): string{
        return parent::render();
    }
}
<?php 

namespace form\type;

use form\InputRender;

abstract class Input implements InputRender{

    protected string $type;
    protected string $label = "";

    protected string $value;
    protected string $name;
    protected string $id;
    protected ?string $function = null;
    protected string $inputValue = "";
    protected ?string $event = null;
    protected bool $checked = false;

    public function __construct(
        string $value,
        protected bool $required,
        string $name,
        string $id,
        string $function =null,
        string $inputValue = "",
        string $event = null,
        bool $checked = false
    ){
        // peut poser problème si on a un espace
        
        $this->value = $value;
        $this->name = str_replace(" ", "", $name);
        $this->id = str_replace(" ", "", $id);
        $this->function = $function;
        $this->inputValue = $inputValue;
        $this->event = $event;
        $this->checked = $checked;
    }

    public function __toString() {
        return $this->render();
    }

    public function setLabel(string $label){
        $this->label = "<label for=".$this->id.">".$label."</label>";
        return $this;
    }

    private function getFonction(): string {
        return $this->function === null ? '' : $this->event . '="' . $this->function . '"';
    }

    public function render(): string{
        $required = $this->required ? 'required="true"' : '';
        $value = $this->value === "" ? '' : 'value="'.$this->value.'"';
        $function = $this->getFonction();
        $checked = $this->checked ? 'checked' : '';
    
        $label = $this->label !== "" ? $this->label : '';
        
        $input = '<input type="'.$this->type.'" '.$required.' '.$value.' id="'.$this->id.'" name="'.$this->name.'" '.$function.' '.$checked.'>';
    
        if($this->type === "radio" || $this->type === "checkbox"){
            return $label . $input . $this->inputValue;
        }
    
        if($this->type === "hidden"){
            return $input;
        }
    
        return $label . $input;
    }
    
}
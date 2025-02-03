<?php

namespace form;

use form\type\Hidden;
use form\type\Link;
use form\type\Submit;

class FormData extends Form
{
    private string $enctype = "multipart/form-data";

    public function __construct(
        protected string $action,
        protected string $method,
        private string $id,
        protected ?string $function = null,
        protected ?string $event = null
    ) {
        parent::__construct($action, $method, $id, $function, $event);
    }

    public function render(): string{
        $function = $this->getFonction();
        $form = "<form class='form' action='".$this->action."' method='".$this->method."' id='".$this->id."' enctype='".$this->enctype."'".$function.">";
        foreach($this->input as $input){
            // affiche les submits à la fin du formulaire
            if($input instanceof Submit){
                continue;
            }

            if($input instanceof Hidden){
                $form .= $input;
                continue;
            }

            if($input instanceof Link){
                $form .= $input;
                continue;
            }
            
            $form .= "<div class='form-input'><div>".$input."</div></div>";
        }

        foreach($this->input as $input){
            if($input instanceof Submit){
                $form .= "<div class='form-input'><div>".$input."</div></div>";
            }
        }
        $form .= "</form>";
        return $form;
    } 
}
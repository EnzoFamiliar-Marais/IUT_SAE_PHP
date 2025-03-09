<?php 

namespace form\type;

class Title extends Input{

    public function __construct(
        private string $title
    ){}

    public function render(): string{
        return "<p class='title'>".$this->title."</p>";
    }
}
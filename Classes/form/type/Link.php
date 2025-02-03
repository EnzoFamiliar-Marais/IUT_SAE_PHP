<?php 

namespace form\type;

class Link extends Input {

    private string $href;
    private string $text;

    public function __construct(
        string $href,
        string $text
    ){
        $this->href = $href;
        $this->text = $text;
    }

    public function __toString(){
        return $this->render();
    }

    public function render(): string{
        return "<a href='".$this->href."'>".$this->text."</a>";
    }
}
<?php

namespace form\type;


class Number extends Input{

    protected string $type = "number";

    public function render(): string{
        return parent::render();
    }
}
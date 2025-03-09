<?php

namespace form\type;


class PasswordField extends Input{

    protected string $type = "password";

    public function render(): string{
        return parent::render();
    }
}
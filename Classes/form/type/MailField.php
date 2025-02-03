<?php

namespace form\type;


class MailField extends Input{

    protected string $type = "email";

    public function render(): string{
        return parent::render();
    }
}
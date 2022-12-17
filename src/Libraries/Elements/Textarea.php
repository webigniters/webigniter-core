<?php

namespace Webigniter\Libraries\Elements;

class Textarea extends Elements
{
    public function output(): string
    {
        return $this->elementData['value'];
    }
}
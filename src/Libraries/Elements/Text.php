<?php

namespace Webigniter\Libraries\Elements;

class Text extends Elements
{
    public function output(): string
    {
        return $this->elementData['value'];
    }
}
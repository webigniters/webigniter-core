<?php
namespace Webigniter\Libraries\Elements;

class Code extends Elements
{
    public function output(): string
    {
        return $this->elementData[$this->fieldName];
    }
}
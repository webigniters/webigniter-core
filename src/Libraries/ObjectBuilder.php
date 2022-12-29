<?php

namespace Webigniter\Libraries;

trait ObjectBuilder
{
    public static function fromParams(array $params): self
    {
        $instance = new self();

        $instance->makeFromRow($params);

        return $instance;
    }

    protected function makeFromRow(array $params): void
    {
        foreach($params as $key => $value)
        {
            $this->$key = $value;
        }
    }
}

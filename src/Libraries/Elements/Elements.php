<?php

namespace Webigniter\Libraries\Elements;

use Webigniter\Models\AttachedPartialsModel;

class Elements
{
    private AttachedPartialsModel $attachedPartialsModel;
    protected array $elementData;
    protected string $fieldName;

    function __construct(int $attachedPartialId, string $fieldName)
    {
        $this->attachedPartialsModel = new AttachedPartialsModel();
        $partial = $this->attachedPartialsModel->find($attachedPartialId);

        $this->fieldName = $fieldName;

        $this->elementData = json_decode($partial->getData(),true);
    }

    public function output(): array|string
    {
        return '';
    }
}
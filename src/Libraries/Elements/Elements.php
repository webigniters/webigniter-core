<?php

namespace Webigniter\Libraries\Elements;

use Webigniter\Models\AttachedElementsModel;

class Elements
{
    private AttachedElementsModel $attachedElementModel;
    public array $elementData;

    function __construct(int $elementId)
    {
        $this->attachedElementModel = new AttachedElementsModel();
        $element = $this->attachedElementModel->find($elementId);
        $this->elementData = json_decode($element->getSettings(),true);
    }

    public function output(): string
    {
        return '';
    }
}
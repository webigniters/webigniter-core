<?php

namespace Webigniter\Libraries\Elements;

use Webigniter\Models\MediaDataModel;

class Media extends Elements
{
    public function output(): array
    {
        $mediaDataModel = new MediaDataModel();

        $media = $mediaDataModel->find($this->elementData[$this->fieldName]);

        if(!$media)
        {
            return [];
        }

        return ['link' => '/media/'.$media->getFilename(), 'alt' => $media->getAlt()];
    }
}
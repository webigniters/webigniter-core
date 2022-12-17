<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\View\Parser;
use Config\Services;
use Webigniter\Models\AttachedElementsModel;
use Webigniter\Models\ContentModel;
use Webigniter\Models\ElementsModel;

class FrontendController extends BaseController
{
    private ContentModel $contentModel;
    private Parser $parser;
    private AttachedElementsModel $attachedElementsModel;
    private ElementsModel $elementModel;

    function __construct()
    {
        $this->contentModel = new ContentModel();
        $this->elementModel = new ElementsModel();
        $this->attachedElementsModel = new AttachedElementsModel();
        $this->parser = Services::parser();
    }

    public function index(int $contentId): string
    {
        $data = [];
        $content = $this->contentModel->find($contentId);

        $attachedElements = $this->attachedElementsModel->where('content_id', $contentId)->findAll();
        foreach($attachedElements as $attachedElement)
        {
            $element = $this->elementModel->find($attachedElement->getElementId());
            $elementClass = $element->getClass();
            $elementObject = new $elementClass($attachedElement->getId());

            $fieldName = json_decode($attachedElement->getSettings())->name;

            $data[$fieldName] = $elementObject->output();

        }

        return $this->parser->setData($data)->render($content->getViewFile());
    }

}
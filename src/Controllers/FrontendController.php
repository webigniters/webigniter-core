<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\View\Parser;
use Config\Services;
use Webigniter\Libraries\Navigation;
use Webigniter\Models\AttachedElementsModel;
use Webigniter\Models\CategoriesModel;
use Webigniter\Models\ContentModel;
use Webigniter\Models\ElementsModel;
use Webigniter\Models\NavigationsModel;

class FrontendController extends BaseController
{
    private ContentModel $contentModel;
    private Parser $parser;
    private AttachedElementsModel $attachedElementsModel;
    private ElementsModel $elementsModel;
    private CategoriesModel $categoriesModel;
    private NavigationsModel $navigationsModel;

    function __construct()
    {
        $this->contentModel = new ContentModel();
        $this->elementsModel = new ElementsModel();
        $this->categoriesModel = new CategoriesModel();
        $this->attachedElementsModel = new AttachedElementsModel();
        $this->navigationsModel = new NavigationsModel();
        $this->parser = Services::parser();
    }

    public function index(int $contentId): string
    {
        $data = [];
        $content = $this->contentModel->find($contentId);
        $category = $this->categoriesModel->find($content->getCategoryId());

        $this->parser->setDelimiters('{{','}}')->setConditionalDelimiters('{{','}}');

        //Parse Elements
        $attachedElements = $this->attachedElementsModel->where('content_id', $contentId)->findAll();
        foreach($attachedElements as $attachedElement)
        {
            $element = $this->elementsModel->find($attachedElement->getElementId());
            $elementClass = $element->getClass();
            $elementObject = new $elementClass($attachedElement->getId());

            $fieldName = json_decode($attachedElement->getSettings())->name;

            $data[$fieldName] = $elementObject->output();
        }

        //Parse Navigations
        $allNavigations = $this->navigationsModel->find();
        foreach($allNavigations as $navigation){
            $data = $this->getNavItems($navigation);
        }

        echo "<pre>";
        print_r($data['nav:footer']);

        //render view file
        $viewContent =  $this->parser->setData($data)->render($content->getViewFile(), ['cascadeData' => true]);

        $data['viewContent'] = $viewContent;

        //render layout file (with contents of view file)
        return $this->parser->setData($data, 'raw')->render($category->getLayoutFile());
    }


    private function getNavItems (Navigation $navigation, $parentId = null): array
    {
        global $navArray;

        $navArray = $navArray ?? [];

        foreach($navigation->getNavigationItems($parentId) as $navigationItem)
        {

            if(!$parentId){
                $navArray['nav:'.$navigation->getName()][$navigationItem->getId()] = [
                    'name' => $navigationItem->getName(),
                    'link' => $navigationItem->getLink(),
                    'depth' => $navigationItem->getDepth(),
                    'children' => []
                ];
            }
            else
            {
                print_r($navigationItem->getParents());
                echo "<hr>";
                $navArray['nav:'.$navigation->getName()][$parentId]['children'][$navigationItem->getId()] = [
                    'name'.$navigationItem->getDepth() => $navigationItem->getName(),
                    'link'.$navigationItem->getDepth() => $navigationItem->getLink(),
                    'children'.$navigationItem->getDepth() => []
                ];
            }

                if($navigationItem->hasChildren())
                {
                    $this->getNavItems($navigation, $navigationItem->getId());
                }
            }

        return $navArray;

    }

}
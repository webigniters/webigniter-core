<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\View\Parser;
use Config\Services;
use Webigniter\Libraries\Navigation;

use Webigniter\Models\AttachedElementsModel;
use Webigniter\Models\AttachedPartialsModel;
use Webigniter\Models\CategoriesModel;
use Webigniter\Models\ContentModel;
use Webigniter\Models\ElementsModel;
use Webigniter\Models\NavigationsModel;
use Webigniter\Models\PartialsModel;

class FrontendController extends BaseController
{
    private ContentModel $contentModel;
    private Parser $parser;

    private ElementsModel $elementsModel;
    private CategoriesModel $categoriesModel;
    private NavigationsModel $navigationsModel;
    private AttachedPartialsModel $attachedPartialsModel;
    private PartialsModel $partialsModel;
    private AttachedElementsModel $attachedElementsModel;

    function __construct()
    {
        $this->contentModel = new ContentModel();
        $this->elementsModel = new ElementsModel();
        $this->categoriesModel = new CategoriesModel();
        $this->partialsModel = new PartialsModel();
        $this->attachedPartialsModel = new AttachedPartialsModel();
        $this->attachedElementsModel = new AttachedElementsModel();
        $this->navigationsModel = new NavigationsModel();
        $this->parser = Services::parser();
    }

    public function index(int $contentId): string
    {
        $data = [];
        $viewContent = '';
        $content = $this->contentModel->find($contentId);
        $category = $this->categoriesModel->find($content->getCategoryId());

        $this->parser->setDelimiters('{{','}}')->setConditionalDelimiters('{{','}}');

        //Parse Partials
        $attachedPartials = $this->attachedPartialsModel->where('content_id', $contentId)->orderBy('order')->findAll();
        foreach($attachedPartials as $attachedPartial){
            $partial = $this->partialsModel->find($attachedPartial->getPartialId());

            $attachedElements = $this->attachedElementsModel->where('partial_id', $attachedPartial->getPartialId())->findAll();

            foreach($attachedElements as $attachedElement)
            {
                $fieldName = json_decode($attachedElement->getSettings())->name;

                $element = $this->elementsModel->find($attachedElement->getElementId());
                $elementClass = $element->getClass();
                $elementObject = new $elementClass($attachedPartial->getId(), $fieldName);

                if(is_array($elementObject->output()))
                {
                    foreach($elementObject->output() as $key => $value)
                    {
                        $data[$fieldName.':'.$key] = $value;
                    }
                }
                else{
                    $data[$fieldName] = $elementObject->output();
                }
            }

            $viewContent .= $this->parser->setData($data)->render($partial->getViewFile(), ['cascadeData' => true]);
        }

        //Parse Navigations
        $allNavigations = $this->navigationsModel->find();
        foreach($allNavigations as $navigation){
            $data['nav:'.$navigation->getName()] = $this->getNavItems($navigation);
        }

        $data['viewContent'] = $viewContent;

        //render layout file (with contents of view file)
        return $this->parser->setData($data, 'raw')->render($category->getLayoutFile());
    }


    private function getNavItems (Navigation $navigation): array
    {
        $returnArray = [];
        $items = $navigation->getNavigationItems();

        foreach($items as $item)
        {
            $returnArray[$item->getId()]['name'] = $item->getName();
            $returnArray[$item->getId()]['link'] = $item->getParsedLink();
            $returnArray[$item->getId()]['children'] = [];

            if($item->hasChildren())
            {
                $children = $item->getChildren();
                foreach($children as $child)
                {
                    $returnArray[$item->getId()]['children'][$child->getId()]['name1'] = $child->getName();
                    $returnArray[$item->getId()]['children'][$child->getId()]['link1'] = $child->getParsedLink();
                    $returnArray[$item->getId()]['children'][$child->getId()]['children1'] = [];

                    if($child->hasChildren())
                    {
                        $subChildren = $child->getChildren();
                        foreach($subChildren as $subChild)
                        {
                            $returnArray[$item->getId()]['children'][$child->getId()]['children1'][$subChild->getId()]['name2'] = $subChild->getName();
                            $returnArray[$item->getId()]['children'][$child->getId()]['children1'][$subChild->getId()]['link2'] = $subChild->getParsedLink();

                        }
                    }
                }
            }
        }

        return $returnArray;
    }
}
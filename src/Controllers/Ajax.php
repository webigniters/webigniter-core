<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use Webigniter\Models\ContentModel;
use Webigniter\Models\NavigationItemsModel;


class Ajax extends BaseController
{
    public function ajax(string $ajaxCall): void
    {
        $this->$ajaxCall();
    }

    private function addNavItem()
    {
        $navigationItemsModel = new NavigationItemsModel();
        $contentModel = new ContentModel();

        $maxOrder = $navigationItemsModel->where('navigation_id', $this->request->getPost('navigation_id'))->selectMax('order')->first();

        $insertData = [
            'navigation_id' => $this->request->getPost('navigation_id'),
            'name' => $this->request->getPost('name'),
            'content_id' => $this->request->getPost('content_id') == '' ? null : $this->request->getPost('content_id'),
            'link' => $this->request->getPost('content_id') != '' ? null : $this->request->getPost('link'),
            'order' => $maxOrder->order+1
        ];

        $navigationItemsModel->insert($insertData);

        if($this->request->getPost('content_id')){
            $content = $contentModel->find($this->request->getPost('content_id'));

            $target = '<span class="fst-italic">'.$content->getName().'</span>';
        }
        else{
            $target = $this->request->getPost('link');
        }

        $result = ['itemId' => $navigationItemsModel->getInsertID(), 'target' => $target];

        echo json_encode($result);
    }


    private function saveNavOrder()
    {
        $navigationItemsModel = new NavigationItemsModel();
        $orderCounter = 1;

        parse_str($_POST['sort'], $order);
        foreach($order['item'] as $navItemId => $parentId)
        {
            $updateData = [
                'order' => $orderCounter,
                'parent_id' => $parentId == 'null' ? null : $parentId
            ];

            $navigationItemsModel->update($navItemId, $updateData);

            $orderCounter++;
        }

    }

    private function deleteNavItem()
    {
        $navigationItemsModel = new NavigationItemsModel();

        $navigationItemsModel->delete($this->request->getPost('item'));
        $navigationItemsModel->where('parent_id', $this->request->getPost('item'))->delete();
    }
}
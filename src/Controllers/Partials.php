<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use CodeIgniter\Validation\Validation;
use Config\Services;
use Webigniter\Libraries\GlobalFunctions;
use Webigniter\Models\AttachedElementsModel;
use Webigniter\Models\AttachedPartialsModel;
use Webigniter\Models\CategoriesModel;
use Webigniter\Models\ContentModel;
use Webigniter\Models\ElementsModel;
use Webigniter\Models\PartialsModel;

class Partials extends BaseController
{
    private Session $session;
    private ElementsModel $elementsModel;
    private AttachedElementsModel $attachedElementsModel;
    private BaseConnection $db;
    private GlobalFunctions $globalFunctions;
    private PartialsModel $partialsModel;
    private AttachedPartialsModel $attachedPartialsModel;

    function __construct()
    {
        $this->partialsModel = new PartialsModel();
        $this->elementsModel = new ElementsModel();
        $this->attachedElementsModel = new AttachedElementsModel();
        $this->attachedPartialsModel = new AttachedPartialsModel();
        $this->globalFunctions = new GlobalFunctions();
        $this->session = Services::session();
        $this->db = db_connect();
    }

    public function list(): string
    {
        $partials = $this->partialsModel->findAll();

        $data['partials'] = $partials;
        $data['breadCrumbs'][] = ['link' => 'partials', 'name' => ucfirst(lang('general.partials'))];

        return view('\Webigniter\Views\partials_list', $data);
    }

    public function add()
    {
        $views = $this->globalFunctions->getViewsList();

        $data['views'] = $views;
        $data['breadCrumbs'][] = ['link' => 'add', 'name' => ucfirst(lang('general.partial_add'))];

        if($this->request->getMethod() == 'get'){

            return view('\Webigniter\Views\partials_add', $data);

        } else{
            $partialData = [
                'name' => $this->request->getPost('name'),
                'view_file' => $this->request->getPost('view_file')
            ];

            if(!$this->partialsModel->insert($partialData)){
                $this->session->setFlashdata('errors', $this->partialsModel->errors());

                return view('\Webigniter\Views\partials_add', $data);

            } else{
                $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', [lang('general.partial')]))]);

                $redirectUrl = url_to('\Webigniter\Controllers\Partials::list');
                return redirect()->to($redirectUrl);
            }
        }
    }

    public function edit(int $partialId)
    {
        $views = $this->globalFunctions->getViewsList();

        $partial = $this->partialsModel->find($partialId);
        $elements = $this->elementsModel->findAll();
        $attachedElements = $this->attachedElementsModel->where('partial_id', $partialId)->orderBy('order')->findAll();

        $data['partial'] = $partial;
        $data['elements'] = $elements;
        $data['attachedElements'] = $attachedElements;
        $data['breadCrumbs'][] = ['link' => 'partials', 'name' => ucfirst(lang('general.partials'))];
        $data['breadCrumbs'][] = ['link' => $partialId, 'name' => $partial->getName()];
        $data['views'] = $views;

        if($this->request->getMethod() == 'get'){

            return view('\Webigniter\Views\partials_edit', $data);
        } else{

            $partialData = [
                'name' => $this->request->getPost('name'),
                'view_file' => $this->request->getPost('view_file'),
            ];

            if(!$this->partialsModel->where('id', $partialId)->set($partialData)->update())
            {
                $this->session->setFlashdata('errors', $this->partialsModel->errors());

                return view('\Webigniter\Views\partials_edit', $data);
            }
            else
            {
                $this->session->setFlashdata('messages', [ucfirst(lang('messages.edit_success', [lang('general.partial')]))]);

                $redirectUrl = url_to('\Webigniter\Controllers\Partials::edit', $partialId);
                return redirect()->to($redirectUrl);
            }
        }
    }

    public function delete(int $partialId): RedirectResponse
    {

        $this->partialsModel->delete($partialId);

        $redirectUrl = url_to('\Webigniter\Controllers\Partials::list');

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.delete_success', [lang('general.partial')]))]);

        return redirect()->to($redirectUrl);
    }

    public function addElement(int $partialId, int $elementId): RedirectResponse
    {
        $duplicateElement = $this->db->query("SELECT `id` FROM `attached_elements` WHERE JSON_EXTRACT(`settings`, '$.name') = ? AND `partial_id` = ?", [$this->request->getPost('name'), $partialId]);
        if($duplicateElement->getNumRows() > 0)
        {
            $this->session->setFlashdata('errors', [ucfirst(lang('errors.is_unique', [lang('general.element'), lang('general.name')]))]);
        }
        else{

            $element = $this->elementsModel->find($elementId);

            $defaultData = [
                'name' => $this->request->getPost('name') ? url_title($this->request->getPost('name'), '-', true) : $element->getName().'-'.strtolower(random_string('nozero', 6)),
                'default_value' => $this->request->getPost('default_value')
            ];

            $data = [
                'partial_id' => $partialId,
                'element_id' => $elementId,
                'settings' => json_encode($defaultData)
            ];

            $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', [lang('general.element')]))]);

            $this->attachedElementsModel->insert($data);

            foreach($this->attachedPartialsModel->where('partial_id', $partialId)->findAll() as $attachedPartial)
            {
                $currentData = json_decode($attachedPartial->getData(), true);
                $currentData[$defaultData['name']] = $defaultData['default_value'];

                $updateData = ['data' => json_encode($currentData)];

                $this->attachedPartialsModel->update($attachedPartial->getId(), $updateData);
            }
        }

        $redirectUrl = url_to('\Webigniter\Controllers\Partials::edit', $partialId);

        return redirect()->to($redirectUrl);
    }

    public function deleteElement(int $partialId, int $attachedElementId): RedirectResponse
    {
        $element = $this->attachedElementsModel->find($attachedElementId);
        $this->attachedElementsModel->delete($attachedElementId);

        foreach($this->attachedPartialsModel->where('partial_id', $partialId)->findAll() as $attachedPartial)
        {
            $attachedPartialData = json_decode($attachedPartial->getData(), true);
            unset($attachedPartialData[json_decode($element->getSettings())->name]);

            $updateArray = ['data' => json_encode($attachedPartialData)];

            $this->attachedPartialsModel->update($attachedPartial->getId(), $updateArray);
        }


        $redirectUrl = url_to('\Webigniter\Controllers\Partials::edit', $partialId);

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.delete_success', [lang('general.element')]))]);

        return redirect()->to($redirectUrl);
    }

}
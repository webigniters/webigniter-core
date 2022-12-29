<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use CodeIgniter\Validation\Validation;
use Config\Services;
use Webigniter\Models\ContentModel;
use Webigniter\Models\NavigationsModel;


class Navigations extends BaseController
{
    private Validation $validation;
    private Session $session;
    private NavigationsModel $navigationsModel;

    function __construct()
    {
        $this->navigationsModel = new NavigationsModel();
        $this->validation = Services::validation();
        $this->session = Services::session();
    }

    public function list(): string
    {
        $navigations = $this->navigationsModel->findAll();

        $data['navigations'] = $navigations;
        $data['breadCrumbs'][] = ['link' => 'navigations', 'name' => ucfirst(lang('general.navigations'))];

        return view('\Webigniter\Views\navigations_list', $data);
    }

    public function add()
    {
        $data['breadCrumbs'][] = ['link' => 'navigations', 'name' => ucfirst(lang('general.navigations'))];
        $data['breadCrumbs'][] = ['link' => 'add', 'name' => ucfirst(lang('general.view_add'))];

        if($this->request->getMethod() === 'get'){

            return view('\Webigniter\Views\navigations_add', $data);
        }
        else{
            $validationRules = [
                'name' => [
                    'rules' => 'required|min_length[3]|alpha_dash',
                    'errors' => [
                        'required' => ucfirst(lang('errors.required', [lang('general.name')])),
                        'min_length' => ucfirst(lang('errors.min_length', [lang('general.name'), 3])),
                        'alpha_dash' => ucfirst(lang('errors.alpha_dash', [lang('general.name')]))
                    ]
                ],
            ];

            $this->validation->setRules($validationRules);

            if(!$this->validation->withRequest($this->request)->run()){
                $errors = $this->validation->getErrors();

                $this->session->setFlashdata('errors', $errors);

                return view('\Webigniter\Views\views_add', $data);
            }

            else{
                $navigationData = [
                    'name' => strtolower($this->request->getPost('name'))
                ];

                $this->navigationsModel->insert($navigationData);

                $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', ['content']))]);

                $redirectUrl = url_to('\Webigniter\Controllers\Navigations::list');
            }
        }
        return redirect()->to($redirectUrl);
    }

    public function edit(string $navigationId)
    {
        $contentModel = new ContentModel();
        $content = $contentModel->findAll();

        $data['content'] = $content;

        if($this->request->getMethod() === 'get')
        {
            $navigation = $this->navigationsModel->find($navigationId);
            $data['navigation'] = $navigation;
            $data['breadCrumbs'][] = ['link' => 'navigations', 'name' => ucfirst(lang('general.navigations'))];
            $data['breadCrumbs'][] = ['link' => 'edit', 'name' => $navigation->getName()];

            return view('\Webigniter\Views\navigations_edit', $data);
        }
        else{

            $this->session->setFlashdata('messages', [ucfirst(lang('messages.edit_success', [lang('general.navigation')]))]);

            $redirectUrl = url_to('\Webigniter\Controllers\Navigations::list');

            return redirect()->to($redirectUrl);

        }
    }

    public function delete(int $navigationId): RedirectResponse
    {
        $this->navigationsModel->delete($navigationId);

        $redirectUrl = url_to('\Webigniter\Controllers\Navigations::list');

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.delete_success', [lang('general.navigation')]))]);

        return redirect()->to($redirectUrl);
    }
}
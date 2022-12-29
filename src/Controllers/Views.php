<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Session\Session;
use CodeIgniter\Validation\Validation;
use Config\Services;
use Webigniter\Libraries\GlobalFunctions;
use Webigniter\Libraries\View;


class Views extends BaseController
{
    private Validation $validation;
    private Session $session;
    private GlobalFunctions $globalFunctions;

    function __construct()
    {
        $this->validation = Services::validation();
        $this->session = Services::session();
        $this->globalFunctions = new GlobalFunctions();
    }

    public function list(): string
    {
        $views = $this->globalFunctions->getViewsList();

        $data['views'] = $views;
        $data['breadCrumbs'][] = ['link' => 'views', 'name' => ucfirst(lang('general.views'))];

        return view('\Webigniter\Views\views_list', $data);
    }

    public function add()
    {
        $data['breadCrumbs'][] = ['link' => 'views', 'name' => ucfirst(lang('general.views'))];
        $data['breadCrumbs'][] = ['link' => 'add', 'name' => ucfirst(lang('general.view_add'))];

        $errors = [];

        if($this->request->getMethod() === 'get'){

            return view('\Webigniter\Views\views_add', $data);
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

            if(file_exists(APPPATH.'/Views/'.$this->request->getPost('name').'.php'))
            {
                $errors = [ucfirst(lang('errors.is_unique',[lang('general.view'), lang('general.name')]))];
            }

            $this->validation->setRules($validationRules);

            if(!$this->validation->withRequest($this->request)->run()){
                $errors = $this->validation->getErrors();
            }

            if(count($errors) > 0)
            {
                $this->session->setFlashdata('errors', $errors);

                return view('\Webigniter\Views\views_add', $data);
            }
            else{
                write_file(APPPATH.'/Views/'.$this->request->getPost('name').'.php','');

                $redirectUrl = url_to('\Webigniter\Controllers\Views::list');
            }
        }
        return redirect()->to($redirectUrl);
    }

    public function edit(string $viewFile)
    {
        if($this->request->getMethod() === 'get')
        {
            $view = View::fromParams(['filename' => $viewFile.'.php']);
            $data['view'] = $view;
            $data['breadCrumbs'][] = ['link' => 'views', 'name' => ucfirst(lang('general.views'))];
            $data['breadCrumbs'][] = ['link' => 'edit', 'name' => $viewFile];

            return view('\Webigniter\Views\views_edit', $data);
        }
        else{
            write_file(APPPATH.'/Views/'.$viewFile.'.php', $this->request->getPost('content'));

            $this->session->setFlashdata('messages', [ucfirst(lang('messages.edit_success', [lang('general.view')]))]);

            $redirectUrl = url_to('\Webigniter\Controllers\Views::list');

            return redirect()->to($redirectUrl);

        }
    }

    public function delete(string $viewFile)
    {
        unlink(APPPATH.'/Views/'.$viewFile.'.php');

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.delete_success', [lang('general.view')]))]);

        $redirectUrl = url_to('\Webigniter\Controllers\Views::list');

        return redirect()->to($redirectUrl);
    }
}
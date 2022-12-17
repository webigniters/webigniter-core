<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Session\Session;
use CodeIgniter\Validation\Validation;
use Config\Services;
use Webigniter\Models\CategoriesModel;
use Webigniter\Models\ContentModel;

class Categories extends BaseController
{
    private CategoriesModel $categoriesModel;
    private Validation $validation;
    private Session $session;
    private ContentModel $contentModel;

    function __construct()
    {
        $this->categoriesModel = new CategoriesModel();
        $this->contentModel = new ContentModel();
        $this->validation = Services::validation();
        $this->session = Services::session();
    }


    public function list(): string
    {
        $categories = $this->categoriesModel->where('parent_id')->orderBy('name')->findAll();

        $data['categories'] = $categories;
        $data['breadCrumbs'][] = ['link' => 'categories', 'name' => ucfirst(lang('general.categories'))];

        return view('\Webigniter\Views\categories_list', $data);
    }

    public function add(int $parentId = null)
    {
        $data['parentId'] = $parentId;

        if($parentId)
        {
            $category = $this->categoriesModel->find($parentId);
            $data['breadCrumbs'] = $category->getBreadCrumbs();
        }
        else{
            $data['breadCrumbs'][] = ['link' => 'categories', 'name' => ucfirst(lang('general.categories'))];
        }
        $data['breadCrumbs'][] = ['link' => 'add', 'name' => ucfirst(lang('general.category_add'))];

        if($this->request->getMethod() == 'get'){

            return view('\Webigniter\Views\categories_add', $data);
        } else{
            $validationRules = [
                'name' => [
                    'rules' => 'required|min_length[3]|is_double_unique[categories,name,parent_id,'.$parentId.']',
                    'errors' => [
                        'required' => ucfirst(lang('errors.required', ['name'])),
                        'min_length' => ucfirst(lang('errors.min_length', ['name', 3])),
                        'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'name']))
                    ]
                ],
                'slug' => [
                    'rules' => 'required|alpha_dash|min_length[3]|is_double_unique[categories,slug,parent_id,'.$parentId.']',
                    'errors' => [
                        'required' => ucfirst(lang('errors.required', ['url'])),
                        'alpha_dash' => ucfirst(lang('errors.alpha_dash', ['url'])),
                        'min_length' => ucfirst(lang('errors.min_length', ['url', 3])),
                        'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'url']))
                    ]
                ]
            ];

            $this->validation->setRules($validationRules);

            if(!$this->validation->withRequest($this->request)->run()){
                $errors = $this->validation->getErrors();
                $this->session->setFlashdata('errors', $errors);

                return view('\Webigniter\Views\categories_add', $data);
            } else{
                $categoryData = [
                    'name' => $this->request->getPost('name'),
                    'slug' => $this->request->getPost('slug'),
                    'parent_id' => $parentId
                ];

                $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', ['category']))]);

                $this->categoriesModel->insert($categoryData);
                $redirectUrl = url_to('\Webigniter\Controllers\Categories::list');
            }

            return redirect()->to($redirectUrl);
        }
    }

    public function edit(int $categoryId)
    {
        $category = $this->categoriesModel->find($categoryId);
        $data['category'] = $category;
        $data['breadCrumbs'] = $category->getBreadCrumbs();

        if($this->request->getMethod() == 'get'){

            return view('\Webigniter\Views\categories_edit', $data);
        } else{
            $validationRules = [
                'name' => [
                    'rules' => 'required|min_length[3]|is_double_unique[categories,name,parent_id,'.$category->getParentId().',id,'.$categoryId.']',
                    'errors' => [
                        'required' => ucfirst(lang('errors.required', ['name'])),
                        'min_length' => ucfirst(lang('errors.min_length', ['name', 3])),
                        'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'name']))
                    ]
                ],
                'slug' => [
                    'rules' => 'required|alpha_dash|min_length[3]|is_double_unique[categories,slug,parent_id,'.$category->getParentId().',id,'.$categoryId.']',
                    'errors' => [
                        'required' => ucfirst(lang('errors.required', ['url'])),
                        'alpha_dash' => ucfirst(lang('errors.alpha_dash', ['url'])),
                        'min_length' => ucfirst(lang('errors.min_length', ['url', 3])),
                        'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'url']))
                    ]
                ]
            ];

            $this->validation->setRules($validationRules);

            if(!$this->validation->withRequest($this->request)->run()){
                $errors = $this->validation->getErrors();
                $this->session->setFlashdata('errors', $errors);

                return view('\Webigniter\Views\categories_edit', $data);
            } else{
                $categoryData = [
                    'name' => $this->request->getPost('name'),
                    'slug' => url_title($this->request->getPost('slug')),
                    'require_slug' => $this->request->getPost('require_slug') == "on" ? 1 : 0
                ];

                $this->session->setFlashdata('messages', [ucfirst(lang('messages.edit_success', ['category']))]);

                $this->categoriesModel->where('id', $categoryId)->set($categoryData)->update();

                if($category->getParentId()){
                    $redirectUrl = url_to('\Webigniter\Controllers\Categories::detail', $category->getParentId());
                } else{
                    $redirectUrl = url_to('\Webigniter\Controllers\Categories::list');
                }
            }

            return redirect()->to($redirectUrl);
        }
    }

    public function detail(int $categoryId): string
    {
        $categories = $this->categoriesModel->where('parent_id', $categoryId)->findAll();
        $category = $this->categoriesModel->find($categoryId);
        $content = $this->contentModel->where('category_id', $categoryId)->findAll();

        $data['categories'] = $categories;
        $data['content'] = $content;
        $data['category'] = $category;
        $data['breadCrumbs'] = $category->getBreadCrumbs();

        return view('\Webigniter\Views\categories_detail', $data);
    }
}
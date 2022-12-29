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
use Webigniter\Models\CategoriesModel;
use Webigniter\Models\ContentModel;
use Webigniter\Models\ElementsModel;

class Content extends BaseController
{
    private Session $session;
    private Validation $validation;
    private ContentModel $contentModel;
    private CategoriesModel $categoriesModel;
    private ElementsModel $elementsModel;
    private AttachedElementsModel $attachedElementsModel;
    private BaseConnection $db;
    private GlobalFunctions $globalFunctions;

    function __construct()
    {
        $this->contentModel = new ContentModel();
        $this->categoriesModel = new CategoriesModel();
        $this->elementsModel = new ElementsModel();
        $this->attachedElementsModel = new AttachedElementsModel();
        $this->globalFunctions = new GlobalFunctions();
        $this->validation = Services::validation();
        $this->session = Services::session();
        $this->db = db_connect();
    }

    public function add(int $categoryId = null)
    {
        $category = $this->categoriesModel->find($categoryId);
        $views = $this->globalFunctions->getViewsList();
        
        $data['views'] = $views;
        
        $data['category'] = $category;
        $data['breadCrumbs'] = $category->getBreadCrumbs();
        $data['breadCrumbs'][] = ['link' => 'add', 'name' => ucfirst(lang('general.content_add'))];

        if($this->request->getMethod() == 'get'){

            return view('\Webigniter\Views\content_add', $data);
        } else{
            $validationRules = [
                'name' => [
                    'rules' => 'required|min_length[3]|is_double_unique[content,name,category_id,'.$categoryId.']',
                    'errors' => [
                        'required' => ucfirst(lang('errors.required', ['name'])),
                        'min_length' => ucfirst(lang('errors.min_length', ['name', 3])),
                        'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'name']))
                    ]
                ],
            ];

            if($this->request->getPost('require_slug') === 'on'){
                $slugValidation = [
                    'slug' => [
                        'rules' => 'required|alpha_dash|is_double_unique[content,slug,category_id,'.$categoryId.']',
                        'errors' => [
                            'required' => ucfirst(lang('errors.required', ['url'])),
                            'alpha_dash' => ucfirst(lang('errors.alpha_dash', ['url'])),
                            'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'url']))
                        ]
                    ]
                ];
            }
            else{
                $slugValidation = [
                    'slug' => [
                        'rules' => 'is_double_unique[content,slug,category_id,'.$categoryId.']',
                        'errors' => [
                            'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'url']))
                        ]
                    ]
                ];
            }

            $validationRules = array_merge($validationRules, $slugValidation);

            $this->validation->setRules($validationRules);

            if(!$this->validation->withRequest($this->request)->run()){
                $errors = $this->validation->getErrors();
                $this->session->setFlashdata('errors', $errors);

                return view('\Webigniter\Views\content_add', $data);
            }
            else{
                $contentData = [
                    'name' => $this->request->getPost('name'),
                    'slug' => url_title($this->request->getPost('slug')),
                    'category_id' => $categoryId,
                    'view_file' => $this->request->getPost('view_file')
                ];

                $this->contentModel->insert($contentData);

                $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', ['content']))]);

                $redirectUrl = url_to('\Webigniter\Controllers\Categories::detail', $categoryId);
            }

            return redirect()->to($redirectUrl);
        }
    }

    public function edit(int $contentId)
    {
        $views = $this->globalFunctions->getViewsList();
        

        $content = $this->contentModel->find($contentId);
        $elements = $this->elementsModel->findAll();
        $attachedElements = $this->attachedElementsModel->where('content_id', $contentId)->findAll();

        $data['content'] = $content;
        $data['elements'] = $elements;
        $data['attachedElements'] = $attachedElements;
        $data['breadCrumbs'] = $content->getBreadCrumbs();
        $data['views'] = $views;

        if($this->request->getMethod() == 'get'){

            return view('\Webigniter\Views\content_edit', $data);
        } else{
            $validationRules = [
                'name' => [
                    'rules' => 'required|min_length[3]|is_double_unique[content,name,category_id,'.$content->getCategoryId().',id,'.$contentId.']',
                    'errors' => [
                        'required' => ucfirst(lang('errors.required', ['name'])),
                        'min_length' => ucfirst(lang('errors.min_length', ['name', 3])),
                        'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'name']))
                    ]
                ],
                'view_file' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => ucfirst(lang('errors.required', ['view file'])),
                    ]
                ]
            ];

            if($this->request->getPost('slug') !== ''){
                $slugValidation = [
                    'slug' => [
                        'rules' => 'alpha_dash|is_double_unique[content,slug,category_id,'.$content->getCategoryId().',id,'.$contentId.']',
                        'errors' => [
                            'alpha_dash' => ucfirst(lang('errors.alpha_dash', ['url'])),
                            'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'url']))
                        ]
                    ]
                ];
            }
            else{
                $slugValidation = [
                    'slug' => [
                        'rules' => 'is_double_unique[content,slug,category_id,'.$content->getCategoryId().',id,'.$contentId.']',
                        'errors' => [
                            'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'url']))
                        ]
                    ]
                ];
            }

            $validationRules = array_merge($validationRules, $slugValidation);

            foreach($this->request->getPost() as $name => $value)
            {
                $this->db->query('UPDATE `attached_elements` SET `settings` = JSON_SET(`settings`, \'$.value\', ?) WHERE JSON_EXTRACT(settings, \'$.name\') = ?', [$value, $name]);
            }

            $this->validation->setRules($validationRules);

            if(!$this->validation->withRequest($this->request)->run()){
                $errors = $this->validation->getErrors();
                $this->session->setFlashdata('errors', $errors);

                return view('\Webigniter\Views\content_edit', $data);
            } else{
                $contentData = [
                    'name' => $this->request->getPost('name'),
                    'slug' => url_title($this->request->getPost('slug')),
                    'view_file' => $this->request->getPost('view_file'),
                    'published' => $this->request->getPost('published') === 'on' ? 1 : 0
                ];

                $this->session->setFlashdata('messages', [ucfirst(lang('messages.edit_success', ['content']))]);

                $this->contentModel->where('id', $contentId)->set($contentData)->update();

                $redirectUrl = url_to('\Webigniter\Controllers\Content::edit', $contentId);
            }

            return redirect()->to($redirectUrl);
        }
    }

    public function delete(int $contentId): RedirectResponse
    {
        $content = $this->contentModel->find($contentId);

        $this->contentModel->delete($contentId);

        $redirectUrl = url_to('\Webigniter\Controllers\Categories::detail', $content->getCategoryId());

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.delete_success', [lang('general.content')]))]);

        return redirect()->to($redirectUrl);
    }

    public function addElement(int $contentId, int $elementId): RedirectResponse
    {
        $duplicateElement = $this->db->query("SELECT `id` FROM `attached_elements` WHERE JSON_EXTRACT(`settings`, '$.name') = ? AND `element_id` = ?", [$this->request->getPost('name'), $elementId]);
        if($duplicateElement->getNumRows() > 0)
        {
            $this->session->setFlashdata('errors', [ucfirst(lang('errors.is_unique', [lang('general.element'), lang('general.name')]))]);
        }
        else{

            $element = $this->elementsModel->find($elementId);

            $defaultData = [
                'name' => $this->request->getPost('name') ? url_title($this->request->getPost('name'), '-', true) : $element->getName().'-'.strtolower(random_string('nozero', 6)),
                'value' => $this->request->getPost('default_value')
            ];

            $data = [
                'content_id' => $contentId,
                'element_id' => $elementId,
                'settings' => json_encode($defaultData)
            ];

            $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', ['element']))]);

            $this->attachedElementsModel->insert($data);
        }

        $redirectUrl = url_to('\Webigniter\Controllers\Content::edit', $contentId);

        return redirect()->to($redirectUrl);
    }

    public function deleteElement(int $contentId, int $attachedElementId): RedirectResponse
    {
        $this->attachedElementsModel->delete($attachedElementId);

        $redirectUrl = url_to('\Webigniter\Controllers\Content::edit', $contentId);

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.delete_success', [lang('general.element')]))]);

        return redirect()->to($redirectUrl);
    }

}
<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use CodeIgniter\Validation\Validation;
use Config\Services;
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

    function __construct()
    {
        $this->contentModel = new ContentModel();
        $this->categoriesModel = new CategoriesModel();
        $this->elementsModel = new ElementsModel();
        $this->attachedElementsModel = new AttachedElementsModel();
        $this->validation = Services::validation();
        $this->session = Services::session();
    }

    public function add(int $categoryId = null)
    {
        $category = $this->categoriesModel->find($categoryId);

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
                'slug' => [
                    'rules' => 'required|alpha_dash|min_length[3]|is_double_unique[content,slug,category_id,'.$categoryId.']',
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

                return view('\Webigniter\Views\content_add', $data);
            } else{
                $contentData = [
                    'name' => $this->request->getPost('name'),
                    'slug' => url_title($this->request->getPost('slug')),
                    'category_id' => $categoryId
                ];

                $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', ['content']))]);

                $this->contentModel->insert($contentData);
                $redirectUrl = url_to('\Webigniter\Controllers\Categories::detail', $categoryId);
            }

            return redirect()->to($redirectUrl);
        }
    }

    public function edit(int $contentId)
    {
        $db = db_connect();
        $content = $this->contentModel->find($contentId);
        $elements = $this->elementsModel->findAll();
        $attachedElements = $this->attachedElementsModel->where('content_id', $contentId)->findAll();

        $data['content'] = $content;
        $data['elements'] = $elements;
        $data['attachedElements'] = $attachedElements;
        $data['breadCrumbs'] = $content->getBreadCrumbs();

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
                'slug' => [
                    'rules' => 'required|alpha_dash|min_length[3]|is_double_unique[content,slug,category_id,'.$content->getCategoryId().',id,'.$contentId.']',
                    'errors' => [
                        'required' => ucfirst(lang('errors.required', ['url'])),
                        'alpha_dash' => ucfirst(lang('errors.alpha_dash', ['url'])),
                        'min_length' => ucfirst(lang('errors.min_length', ['url', 3])),
                        'is_double_unique' => ucfirst(lang('errors.is_unique', ['category', 'url']))
                    ]
                ]
            ];

            foreach($this->request->getPost() as $name => $value)
            {
                $db->query('UPDATE `attached_elements` SET `settings` = JSON_SET(`settings`, \'$.value\', ?) WHERE JSON_EXTRACT(settings, \'$.name\') = ?', [$value, $name]);
            }

            $this->validation->setRules($validationRules);

            if(!$this->validation->withRequest($this->request)->run()){
                $errors = $this->validation->getErrors();
                $this->session->setFlashdata('errors', $errors);

                return view('\Webigniter\Views\content_edit', $data);
            } else{
                $contentData = [
                    'name' => $this->request->getPost('name'),
                    'slug' => url_title($this->request->getPost('slug'))
                ];

                $this->session->setFlashdata('messages', [ucfirst(lang('messages.edit_success', ['content']))]);

                $this->contentModel->where('id', $contentId)->set($contentData)->update();

                $redirectUrl = url_to('\Webigniter\Controllers\Content::edit', $contentId);
            }

            return redirect()->to($redirectUrl);
        }
    }

    public function addElement(int $contentId, int $elementId): RedirectResponse
    {
        $element = $this->elementsModel->find($elementId);

        $defaultData = [
            'name' => $this->request->getPost('name') ?: $element->getName().'-'.strtolower(random_string('nozero', 6)),
            'value' => $this->request->getPost('default_value')
        ];

        $data = [
            'content_id' => $contentId,
            'element_id' => $elementId,
            'settings' => json_encode($defaultData)
        ];

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', ['element']))]);

        $this->attachedElementsModel->insert($data);

        $redirectUrl = url_to('\Webigniter\Controllers\Content::edit', $contentId);

        return redirect()->to($redirectUrl);
    }
}
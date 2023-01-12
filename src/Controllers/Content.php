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
use Webigniter\Models\PartialsModel;

class Content extends BaseController
{
    private Session $session;
    private Validation $validation;
    private ContentModel $contentModel;
    private CategoriesModel $categoriesModel;
    private BaseConnection $db;
    private GlobalFunctions $globalFunctions;
    private AttachedPartialsModel $attachedPartialsModel;
    private PartialsModel $partialsModel;

    function __construct()
    {
        $this->contentModel = new ContentModel();
        $this->categoriesModel = new CategoriesModel();
        $this->partialsModel = new PartialsModel();
        $this->attachedPartialsModel = new AttachedPartialsModel();
        $this->globalFunctions = new GlobalFunctions();
        $this->validation = Services::validation();
        $this->session = Services::session();
        $this->db = db_connect();
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
            } else{
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
            } else{
                $contentData = [
                    'name' => $this->request->getPost('name'),
                    'slug' => url_title($this->request->getPost('slug')),
                    'category_id' => $categoryId
                ];

                $this->contentModel->insert($contentData);

                $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', [lang('general.content')]))]);

                $redirectUrl = url_to('\Webigniter\Controllers\Categories::detail', $categoryId);
            }

            return redirect()->to($redirectUrl);
        }
    }

    public function edit(int $contentId)
    {
        $content = $this->contentModel->find($contentId);
        $partials = $this->partialsModel->findAll();
        $attachedPartials = $this->attachedPartialsModel->where('content_id', $contentId)->findAll();

        $data['content'] = $content;
        $data['partials'] = $partials;
        $data['attachedPartials'] = $attachedPartials;
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
            } else{
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

            $this->validation->setRules($validationRules);

            if(!$this->validation->withRequest($this->request)->run()){
                $errors = $this->validation->getErrors();
                $this->session->setFlashdata('errors', $errors);

                return view('\Webigniter\Views\content_edit', $data);
            } else{
                $contentData = [
                    'name' => $this->request->getPost('name'),
                    'slug' => url_title($this->request->getPost('slug')),
                    'published' => $this->request->getPost('published') === 'on' ? 1 : 0
                ];

                $this->session->setFlashdata('messages', [ucfirst(lang('messages.edit_success', [lang('general.content')]))]);

                $this->contentModel->where('id', $contentId)->set($contentData)->update();

                foreach($attachedPartials as $attachedPartial)
                {
                    $jsonData = [];

                    $elements = $attachedPartial->getPartialElements();
                    foreach($elements as $element)
                    {
                        $elementSettings = json_decode($element->getSettings());
                        $jsonData[$elementSettings->name] = $this->request->getPost($attachedPartial->getId().":".$elementSettings->name);
                    }

                    $updateData = ['data' => json_encode($jsonData)];

                    $this->attachedPartialsModel->update($attachedPartial->getId(), $updateData);

                }

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

    public function addPartial(int $contentId): RedirectResponse
    {

        $attachedElementsModel = new AttachedElementsModel();

        $attachedElements = $attachedElementsModel->where('partial_id', $this->request->getPost('partial_id'))->find();

        foreach($attachedElements as $attachedElement)
        {
            $elementSettings = $attachedElement->getSettingsArray();

            $defaultValues[$elementSettings['name']] = $elementSettings['default_value'];
        }

        $insertData = [
            'content_id' => $contentId,
            'partial_id' => $this->request->getPost('partial_id'),
            'data' => json_encode($defaultValues)
        ];


        $this->attachedPartialsModel->insert($insertData);

        $redirectUrl = url_to('\Webigniter\Controllers\Content::edit', $contentId);

        return redirect()->to($redirectUrl);
}


    public function deletePartial(int $contentId, int $attachedPartialId): RedirectResponse
    {
        $this->attachedPartialsModel->delete($attachedPartialId);

        $redirectUrl = url_to('\Webigniter\Controllers\Content::edit', $contentId);

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.delete_success', [lang('general.partial')]))]);

        return redirect()->to($redirectUrl);
    }

}
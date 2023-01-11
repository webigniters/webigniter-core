<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Session\Session;
use Config\Services;
use Webigniter\Libraries\MediaData;
use Webigniter\Models\MediaDataModel;


class Media extends BaseController
{
    private Session $session;
    private MediaDataModel $mediaDataModel;

    function __construct()
    {
        $this->session = Services::session();
        $this->mediaDataModel = new MediaDataModel();
    }

    public function list(...$segments): string
    {
        $currentFolder = implode('/', $segments);

        $data['breadCrumbs'][] = ['link' => 'media', 'name' => ucfirst(lang('general.media'))];

        $link = 'media/';
        foreach($segments as $segment)
        {
            if($segment !== '-')
            {
                $link .= $segment.'/';
                $data['breadCrumbs'][] = ['link' => $link, 'name' => $segment];
            }
        }

        if(is_file(FCPATH.'/media/'.$currentFolder))
        {
            return $this->fileEditor($currentFolder, $data['breadCrumbs']);
        }

        $media = MediaData::getMediaDirectory($currentFolder);


        $data['currentFolder'] = $currentFolder;
        $data['media'] = $media;

        return view('\Webigniter\Views\media_list', $data);
    }

    public function add()
    {
        $folder = FCPATH.'/media/'.$this->request->getPost('current_folder');

        $allMedia = $this->request->getFiles();

        foreach($allMedia['media'] as $mediaFile)
        {
            $mediaFile->move($folder);

            $filename = $this->request->getPost('current_folder').'/'.$mediaFile->getName();

            if(str_starts_with($filename, '/')){
                $filename = substr($filename, 1);
            }

            $this->mediaDataModel->insert(['filename' => $filename]);
        }

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', [lang('general.media')]))]);

        $redirectUrl = url_to('\Webigniter\Controllers\Media::list', $this->request->getPost('current_folder'));

        return redirect()->to($redirectUrl);
    }

    public function addFolder()
    {
        $newFolder = FCPATH.'/media/'.$this->request->getPost('current_folder').'/'.$this->request->getPost('name');

        if(!is_dir($newFolder)){
            mkdir($newFolder);

            $this->session->setFlashdata('messages', [ucfirst(lang('messages.create_success', [lang('general.folder')]))]);
        }

        $redirectUrl = url_to('\Webigniter\Controllers\Media::list', $this->request->getPost('current_folder'));

        return redirect()->to($redirectUrl);
    }

    public function edit(string ...$segments)
    {
        $media = implode('/', $segments);
        $media = str_replace('..', '', $media);

        array_pop($segments);
        $currentFolder = implode('/', $segments);

        rename(FCPATH.'/media/'.$media, FCPATH.'/media/'.$currentFolder.'/'.$this->request->getPost('filename'));

        $filename = $currentFolder.'/'.$this->request->getPost('filename');

        if(str_starts_with($filename, '/')){
            $filename = substr($filename, 1);
        }

        $mediaData['id'] = $this->request->getPost('custom_data');
        $mediaData['filename'] = $filename;
        $mediaData['alt'] = $this->request->getPost('alt');

        $this->mediaDataModel->save($mediaData);

        $redirectUrl = url_to('\Webigniter\Controllers\Media::list', $currentFolder);

        return redirect()->to($redirectUrl);
    }

    public function delete(string ...$segments)
    {
        $media = implode('/', $segments);
        $media = str_replace('..', '', $media);

        if(is_dir(FCPATH.'/media/'.$media))
        {
            delete_files(FCPATH.'/media/'.$media);
            rmdir(FCPATH.'/media/'.$media);
        }
        else
        {
            $this->mediaDataModel->where('filename', basename($media))->delete();
            unlink(FCPATH.'/media/'.$media);
        }

        $this->session->setFlashdata('messages', [ucfirst(lang('messages.delete_success', [lang('general.media')]))]);

        array_pop($segments);
        $currentFolder = implode('/', $segments);

        $redirectUrl = url_to('\Webigniter\Controllers\Media::list', $currentFolder);

        return redirect()->to($redirectUrl);
    }

    private function fileEditor(string $file, array $breadCrumbs): string
    {
        $customMediaData = $this->mediaDataModel->where('filename', $file)->first();

        $filesize = filesize(FCPATH.'/media/'.$file);

        $data['breadCrumbs'] = $breadCrumbs;
        $data['file'] = '/media/'.$file;
        $data['customData'] = $customMediaData;
        $data['filesize'] = MediaData::readableSize($filesize);
        $data['mimeType'] = mime_content_type(FCPATH.'/media/'.$file);

        return view('\Webigniter\Views\media_file_editor', $data);
    }
}
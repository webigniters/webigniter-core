<?php

namespace Webigniter\Libraries;

use Webigniter\Models\MediaDataModel;

class MediaData
{
    private int $id;
    private string $filename;
    private string $alt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }



    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }



    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @param string $alt
     */
    public function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }


    /** CUSTOM METHODS */

    public static function checkMediaData(string $media): int
    {
        if(str_starts_with($media, '/')){
            $media = substr($media, 1);
        }

        $mediaDataModel = new MediaDataModel();
        $foundMedia = $mediaDataModel->where('filename', $media)->first();
        if(!$foundMedia)
        {
            $mediaDataModel->insert(['filename' => $media]);

            return $mediaDataModel->getInsertID();
        }

        return $foundMedia->getId();
    }

    public static function getMediaDirectory(string $currentFolder): array
    {
        $folders = [];
        $files = [];

        $directoryMap = directory_map(FCPATH.'/media/'.$currentFolder, 1);

        if(strlen($currentFolder) > 0){
            $folders[] = ['name' => '..', 'icon' => 'fas fa-folder', 'type' => 'folder', 'size' => ''];
        }

        foreach($directoryMap as $directoryContent)
        {
            $directoryContent = str_replace('\\', '', $directoryContent);

            if(is_dir(FCPATH.'/media/'.$currentFolder.'/'.$directoryContent))
            {
                $folders[] = ['name' => $directoryContent, 'icon' => 'fas fa-folder', 'type' => 'folder', 'size' => '', 'id' => ''];
            }
            else{
                $filesize = filesize(FCPATH.'/media/'.$currentFolder.'/'.$directoryContent);
                $dataId = MediaData::checkMediaData($currentFolder.'/'.$directoryContent);

                $files[] = ['name' => $directoryContent, 'icon' => (new self)->getFileIcon($currentFolder, $directoryContent), 'type' => 'file', 'size' => (new self)->readableSize($filesize), 'id' => $dataId];
            }
        }

        return array_merge($folders,$files);
    }

    public static function readableSize(int $bytes): string
    {
        if ($bytes == 0){
            return "0.00 B";
        }

        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $calculatedSize = floor(log($bytes, 1024));

        return round($bytes/pow(1024, $calculatedSize), 2).' '.$units[$calculatedSize];
    }

    private function getFileIcon(string $folder, string $fileName): string
    {
        $knownTypes = [
            'txt' => 'far fa-file-alt',
            'ini' => 'far fa-file-alt',

            'mp3' => 'far fa-file-audio',
            'wav' => 'far fa-file-audio',
            'midi' => 'far fa-file-audio',

            'css' => 'far fa-file-code',
            'php' => 'far fa-file-code',
            'scss' => 'far fa-file-code',

            'csv' => 'far fa-file-excel',
            'xls' => 'far fa-file-excel',
            'xlsx' => 'far fa-file-excel',

            'png' => 'far fa-file-image',
            'bmp' => 'far fa-file-image',
            'jpg' => 'far fa-file-image',
            'jpeg' => 'far fa-file-image',

            'pdf' => 'far fa-file-pdf',

            'mp4' => 'far fa-file-video',
            'avi' => 'far fa-file-video',

            'doc' => 'far fa-file-word',
            'docx' => 'far fa-file-word',

        ];

        $extension =  pathinfo(FCPATH.'/media/'.$folder.'/'.$fileName, PATHINFO_EXTENSION );

        if(key_exists($extension, $knownTypes))
        {
            return $knownTypes[$extension];
        }
        else
        {
            return 'far fa-file';
        }
    }
}
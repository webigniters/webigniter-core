<?php

namespace Webigniter\Libraries;

use Webigniter\Models\CategoriesModel;
use Webigniter\Models\ContentModel;

class View
{
    use ObjectBuilder;

    private string $filename;

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /** CUSTOM METHODS */

    public function getName(): string
    {
        return substr($this->filename,0,-4);
    }

    public function getContents(): string
    {
        return file_get_contents(APPPATH.'/Views/'.$this->filename);
    }

    public function getNumUsages(): int
    {
        $contentUsages = 0;
        $contentModel = new ContentModel();
        $categoriesModel = new CategoriesModel();

        $contentCount = $contentModel->where('view_file', $this->filename)->countAllResults();
        $categoriesCount = $categoriesModel->where('layout_file', $this->filename)->findAll();
        foreach($categoriesCount as $category)
        {
            $contentUsages += $contentModel->where('category_id', $category->getId())->countAllResults();
        }

        return ($contentCount+$contentUsages);
    }
}
<?php

namespace Webigniter\Libraries;

use Webigniter\Models\CategoriesModel;

class Content
{
    use ObjectBuilder;

    private int $id;
    private string $name;
    private string $slug;
    private string $view_file;
    private int $category_id;
    private bool $published;
    private string $created_at;
    private string $updated_at;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getViewFile(): string
    {
        return $this->view_file;
    }



    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published;
    }



    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @param string $view_file
     */
    public function setViewFile(string $view_file): void
    {
        $this->view_file = $view_file;
    }


    /**
     * @param int $category_id
     */
    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }

    /**
     * @param bool $published
     */
    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }



    /**
     * @param string $created_at
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /** CUSTOM METHODS */

    public function getBreadCrumbs(): array
    {
        $categoriesModel = new CategoriesModel();
        $urlSegments[] = ['link' => '/category/'.$this->getId(), 'name' => $this->getName()];

        $parentId = $this->getCategoryId();
        while ($parentId) {
            $parentData = $categoriesModel->find($parentId);

            $parentId = $parentData->getParentId();

            $urlSegments[] = ['link' => '/category/'.$parentData->getId(), 'name' => $parentData->getName()];
        }

        $urlSegments[] = ['link' => '/categories', 'name' => ucfirst(lang('general.categories'))];

        return array_reverse($urlSegments);
    }

    public function getFullUrl(): string
    {
        $categoriesModel = new CategoriesModel();

        $urlSegments[] = $this->getSlug();
        $parentId = $this->getCategoryId();

        while ($parentId) {
            $parentData = $categoriesModel->find($parentId);

            $parentId = $parentData->getParentId();

            if($parentData->isRequireSlug() && strlen($parentData->getSlug()) > 0){
                $urlSegments[] = $parentData->getSlug();
            }
        }

        return implode('/', array_reverse($urlSegments));

    }

}
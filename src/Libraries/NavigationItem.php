<?php

namespace Webigniter\Libraries;

use Webigniter\Models\NavigationItemsModel;

class NavigationItem
{
    private int $id;
    private int $navigation_id;
    private ?int $parent_id;
    private string $name;
    private ?int $content_id;
    private ?string $link;
    private int $depth = 0;
    private array $parents = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNavigationId(): int
    {
        return $this->navigation_id;
    }

    /**
     * @return int
     */
    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getContentId(): ?int
    {
        return $this->content_id;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }


    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $navigation_id
     */
    public function setNavigationId(int $navigation_id): void
    {
        $this->navigation_id = $navigation_id;
    }

    /**
     * @param int $parent_id
     */
    public function setParentId(?int $parent_id): void
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int|null $content_id
     */
    public function setContentId(?int $content_id): void
    {
        $this->content_id = $content_id;
    }

    /**
     * @param string|null $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }



    /** CUSTOM METHODS */


    public function hasChildren(): bool

    {
        $navigationItemsModel = new NavigationItemsModel();

        $numChildren = $navigationItemsModel->where('parent_id', $this->id)->countAllResults();

        return $numChildren>0;
    }

    public function getDepth(): int
    {
//        if($this->depth > 0)
//        {
//            return $this->depth;
//        }

        $navigationItemsModel = new NavigationItemsModel();

        if($this->getParentId())
        {
            $this->depth++;
            $parent = $navigationItemsModel->find($this->getParentId());

            $parent->getDepth();
        }

        return $this->depth;
    }

    public function getParents(): array
    {
        global $parents;

        $navigationItemsModel = new NavigationItemsModel();

        if($this->getParentId())
        {
            $parents[] = $this->getParentId();
            $parent = $navigationItemsModel->find($this->getParentId());

            $parent->getParents();
        }

        return $parents;
    }
}
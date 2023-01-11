<?php

namespace Webigniter\Libraries;

use Webigniter\Models\ContentModel;
use Webigniter\Models\NavigationItemsModel;

class NavigationItem
{
    private int $id;
    private int $navigation_id;
    private ?int $parent_id;
    private string $name;
    private ?int $content_id;
    private ?string $link;
    public ?array $children = null;

    function __construct()
    {
        if(isset($this->id) && $this->hasChildren())
        {
            $this->children = $this->getChildren();
        }
    }

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


    public function getChildren(): array
    {
        $navigationItemsModel = new NavigationItemsModel();

        return $navigationItemsModel->where('parent_id', $this->id)->orderBy('order')->findAll();

    }

    public function getParsedLink(): string
    {
        if($this->content_id)
        {
            $contentModel = new ContentModel();
            $content = $contentModel->find($this->content_id);

            return "/".$content->getFullUrl();
        }
        else
        {
            return $this->link;
        }
    }
}
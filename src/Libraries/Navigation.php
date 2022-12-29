<?php

namespace Webigniter\Libraries;

use Webigniter\Models\NavigationItemsModel;

class Navigation
{
    private int $id;
    private string $name;

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


    /** CUSTOM METHODS */

    public function getNavigationItems(?int $parentId = null): array
    {
        $navigationItemsModel = new NavigationItemsModel();

        $items = $navigationItemsModel->where('navigation_id', $this->id)->where('parent_id', $parentId)->orderBy('order')->findAll();

        return $items;
    }
}
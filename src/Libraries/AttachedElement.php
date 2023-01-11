<?php

namespace Webigniter\Libraries;

use Webigniter\Models\ElementsModel;

class AttachedElement
{
    private int $id;
    private int $content_id;
    private int $element_id;
    private string $settings;

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
    public function getContentId(): int
    {
        return $this->content_id;
    }

    /**
     * @return int
     */
    public function getElementId(): int
    {
        return $this->element_id;
    }

    /**
     * @return string
     */
    public function getSettings(): string
    {
        return $this->settings;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $content_id
     */
    public function setContentId(int $content_id): void
    {
        $this->content_id = $content_id;
    }

    /**
     * @param int $element_id
     */
    public function setElementId(int $element_id): void
    {
        $this->element_id = $element_id;
    }

    /**
     * @param string $settings
     */
    public function setSettings(string $settings): void
    {
        $this->settings = $settings;
    }

    /** CUSTOM METHODS */

    public function getElementName(): string
    {
        $elementsModel = new ElementsModel();

        $element = $elementsModel->find($this->getElementId());

        return $element->getName();
    }

    public function getElementPartial(): string
    {
        $elementsModel = new ElementsModel();

        $element = $elementsModel->find($this->getElementId());

        return $element->getPartial();
    }

    public function getSettingsArray(): array
    {
        return json_decode($this->settings, true);
    }
}
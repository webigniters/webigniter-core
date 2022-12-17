<?php

namespace Webigniter\Libraries;

class Element
{
    private int $id;
    private string $name;
    private string $language;
    private string $image;
    private string $partial;
    private string $settings;

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
    public function getLanguage(): string
    {
        return $this->language;
    }


    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getPartial(): string
    {
        return $this->partial;
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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }



    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @param string $partial
     */
    public function setPartial(string $partial): void
    {
        $this->partial = $partial;
    }


    /**
     * @param string $settings
     */
    public function setSettings(string $settings): void
    {
        $this->settings = $settings;
    }


}
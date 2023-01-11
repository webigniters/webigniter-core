<?php

namespace Webigniter\Libraries;

use Webigniter\Models\AttachedElementsModel;
use Webigniter\Models\ElementsModel;
use Webigniter\Models\PartialsModel;

class AttachedPartial
{
    private int $id;
    private int $content_id;
    private int $partial_id;
    private string $data;

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
    public function getPartialId(): int
    {
        return $this->partial_id;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
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
     * @param int $partial_id
     */
    public function setPartialId(int $partial_id): void
    {
        $this->partial_id = $partial_id;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }





    /** CUSTOM METHODS */


    public function getDataArray(): array
    {
        return json_decode($this->data, true) ?? [];
    }

    public function getPartialName(): string
    {
        $partialsModel = new PartialsModel();

        $partial = $partialsModel->find($this->getPartialId());

        return $partial->getName();
    }

    public function getPartialElements(): array
    {
        $attachedElementsModel = new AttachedElementsModel();

        return $attachedElementsModel->where('partial_id', $this->getPartialId())->findAll() ?? [];

    }
}
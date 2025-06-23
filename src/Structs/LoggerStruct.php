<?php

namespace Layman\LaravelLogger\Structs;
class LoggerStruct
{
    protected int $userid;
    protected string $type;
    protected ?string $model = null;
    protected array $old = [];
    protected array $new = [];

    /**
     * @return int
     */
    protected function getUserid(): int
    {
        return $this->userid;
    }

    /**
     * @param int $userid
     * @return LoggerStruct
     */
    public function setUserid(int $userid): static
    {
        $this->userid = $userid;
        return $this;
    }

    /**
     * @return string
     */
    protected function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return LoggerStruct
     */
    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return string|null
     */
    protected function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string|null $model
     * @return LoggerStruct
     */
    public function setModel(?string $model): static
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return array
     */
    protected function getOld(): array
    {
        return $this->old;
    }

    /**
     * @param array $old
     * @return LoggerStruct
     */
    public function setOld(array $old): static
    {
        $this->old = $old;
        return $this;
    }

    /**
     * @return array
     */
    protected function getNew(): array
    {
        return $this->new;
    }

    /**
     * @param array $new
     * @return LoggerStruct
     */
    public function setNew(array $new): static
    {
        $this->new = $new;
        return $this;
    }
}

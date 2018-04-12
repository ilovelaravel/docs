<?php namespace Ill\Docs\Annotations;

/** @Annotation */
class Info
{
    /**
     * @var string
     */
    public $info;

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }
}
<?php namespace Ill\Docs\Annotations;

/** @Annotation */
class Title
{
    /**
     * @var string
     */
    public $title;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
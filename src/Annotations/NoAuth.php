<?php namespace Ill\Docs\Annotations;

/** @Annotation */
class NoAuth
{
    /**
     * @var boolean
     */
    public $needAuth;

    /**
     * @return boolean
     */
    public function getNeedAuth()
    {
        return $this->needAuth;
    }
}
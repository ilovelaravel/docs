<?php namespace Ill\Docs\Contracts;

interface ApiRequestContract
{
    public function rules();
    public function schema();
}
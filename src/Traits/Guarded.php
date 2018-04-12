<?php namespace Ill\Docs\Traits;

/**
 * Trait Guarded
 * use in a Laravel FormRequest class
 */
trait Guarded
{
    /**
     * Get all input parameter that are guarded by and
     * match the rules array of the form request
     *
     * @return mixed
     */
    public function guarded()
    {
        return $this->all();
    }

    /**
     * We extend the all() method of the parent,
     * without breaking current code using it
     *
     * @param null $keys
     * @return mixed
     */
    public function all($keys = null)
    {
        $guarded = array_keys($this->rules());
        $merged  = array_merge($keys ?? [], $guarded);

        return parent::all(array_unique($merged));
    }
}
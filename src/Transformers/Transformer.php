<?php namespace Ill\Docs\Transformers;

abstract class Transformer
{
    /**
     * Transform Entities
     *
     * @param  array $entities
     * @return array
     */
    public function transformEntities(array $entities = []): array
    {
        return array_map([$this, 'transform'], $entities);
    }

    /**
     * Transform a collection item to follow(-ish) a specific format
     * (see: http://jsonapi.org/format )
     * @param  $item
     * @return array
     */
    abstract public function transform($item);
}
<?php

namespace App\Model;

use ArrayAccess;
use Countable;
use Iterator;

class InputMetaCollection implements ArrayAccess, Iterator, Countable
{
    /** @var array $metas */
    private $metas = [];


    public function add(InputMeta $inputMeta)
    {
        $this->metas[] = $inputMeta;
    }

    /**
     * @param string $variableName
     * @return InputMeta|null
     */
    public function findMetaByVariableName(string $variableName): ?InputMeta
    {
        /** @var InputMeta $meta */
        foreach ($this->metas as $meta) {
            if ($variableName === $meta->getVariable()) {
                return $meta;
            }
        }
        return null;
    }

    public function current()
    {
        return current($this->metas);
    }

    public function next()
    {
        return next($this->metas);
    }

    public function key()
    {
        return key($this->metas);
    }

    public function valid()
    {
        return (false !== current($this->metas));
    }

    public function rewind()
    {
        reset($this->metas);
    }

    public function offsetExists($offset)
    {
        return isset($this->metas[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->metas[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->metas[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->metas[$offset]);
    }

    public function count()
    {
        return count($this->metas);
    }
}

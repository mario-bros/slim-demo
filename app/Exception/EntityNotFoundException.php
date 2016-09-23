<?php

namespace SlimDemo\Exception;

final class EntityNotFoundException extends HttpException
{
    /**
     * @param string $class
     * @param string $id
     * @return EntityNotFoundException
     */
    public static function create(string $class, string $id): self
    {
        return new self(sprintf('Entity %s not found with id %s', $class, $id), 404);
    }
}

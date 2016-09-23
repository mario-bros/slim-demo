<?php

namespace SlimDemo\Exception;

final class BadRequestException extends HttpException
{
    /**
     * @param string $argument
     *
     * @return BadRequestException
     */
    public static function createForMissingArgument(string $argument): self
    {
        return new self(sprintf('Missing argument %s', $argument), 400);
    }
}

<?php
namespace TRegx\Analyze\Simplify\Model;

abstract class Model
{
    public abstract function getContent(): string;

    public function isSingleToken(): bool
    {
        return false;
    }
}

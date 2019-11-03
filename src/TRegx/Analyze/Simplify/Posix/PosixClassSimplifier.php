<?php
namespace TRegx\Analyze\Simplify\Posix;

class PosixClassSimplifier
{
    /** @var string[] */
    private $elements;

    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    public function all(): array
    {
        return $this->elements;
    }
}

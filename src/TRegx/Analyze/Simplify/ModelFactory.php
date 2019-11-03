<?php
namespace TRegx\Analyze\Simplify;

use TRegx\Analyze\Simplify\Model\EscapedLiteral;
use TRegx\Analyze\Simplify\Model\Literal;
use TRegx\Analyze\Simplify\Model\Model;
use TRegx\Analyze\Simplify\Model\Quote;

class ModelFactory
{
    public function model(string $piece): Model
    {
        if ($this->isEscapedLiteral($piece)) {
            return new EscapedLiteral($piece);
        }
        if ($this->isQuote($piece)) {
            return new Quote($piece);
        }
        return new Literal($piece);
    }

    private function isEscapedLiteral(string $piece): bool
    {
        return strlen($piece) === 2 && $piece[0] == '\\';
    }

    private function isQuote(string $piece): bool
    {
        return substr($piece, 0, 2) === '\\Q';
    }
}

<?php
namespace Test\TRegx\Analyze\Simplify\Alternative;

use PHPUnit\Framework\TestCase;
use TRegx\Analyze\Simplify\Alternative\LiteralAltSplitter;
use TRegx\Analyze\Simplify\Model\AltEnd;
use TRegx\Analyze\Simplify\Model\AltStart;
use TRegx\Analyze\Simplify\Model\EscapedLiteral;
use TRegx\Analyze\Simplify\Model\Literal;

class LiteralAltSplitterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldTransform()
    {
        // given
        $models = [
            new Literal('before'),
            new Literal('open (?: nested (?:a|b|'),
            new EscapedLiteral('\.'),
            new Literal('|c) close'),
            new Literal('open (?:a|b|'),
            new Literal('|c) close'),
            new Literal(') close'),
            new Literal('end'),
        ];
        $factory = new LiteralAltSplitter($models);

        // when
        $transformed = $factory->split();

        // then
        $expected = [
            new Literal('before'),
            new Literal('open '),
            new AltStart(),
            new Literal(' nested '),
            new AltStart(),
            new Literal('a|b|'),
            new EscapedLiteral('\.'),
            new Literal('|c'),
            new AltEnd(),
            new Literal(' close'),
            new Literal('open '),
            new AltStart(),
            new Literal('a|b|'),
            new Literal('|c'),
            new AltEnd(),
            new Literal(' close'),
            new AltEnd(),
            new Literal(' close'),
            new Literal('end'),
        ];
        $this->assertEquals($expected, $transformed);
    }
}

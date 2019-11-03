<?php
namespace TRegx\Analyze\Simplify\Alternative;

use TRegx\Analyze\Simplify\Model\AltEnd;
use TRegx\Analyze\Simplify\Model\AltStart;
use TRegx\Analyze\Simplify\Model\Literal;
use TRegx\Analyze\Simplify\Model\Model;
use TRegx\Analyze\Simplify\ModelList;

class LiteralAltSplitter
{
    /** @var Model[] */
    private $models;

    /** @var ModelList */
    private $result;

    public function __construct(array $models)
    {
        $this->models = $models;
        $this->result = new ModelList();
    }

    public function split(): array
    {
        foreach ($this->models as $model) {
            if ($model instanceof Literal) {
                $elements = $this->transformLiteral($model);
                $this->result->addAll($elements);
            } else {
                $this->result->add($model);
            }
        }
        return $this->result->getAndClear();
    }

    private function transformLiteral(Literal $model): array
    {
        return $this->createModels($this->splitContent($model->getContent()));
    }

    private function splitContent(string $content): array
    {
        return preg_split('/(\(\?:|\))/', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }

    private function createModels(array $elements): array
    {
        return array_map(function (string $string) {
            if ($string === ')') {
                return new AltEnd();
            }
            if ($string === '(?:') {
                return new AltStart();
            }
            return new Literal($string);
        }, $elements);
    }
}

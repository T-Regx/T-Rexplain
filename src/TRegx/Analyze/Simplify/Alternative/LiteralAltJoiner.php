<?php
namespace TRegx\Analyze\Simplify\Alternative;

use TRegx\Analyze\InternalException;
use TRegx\Analyze\Simplify\Model\Alt;
use TRegx\Analyze\Simplify\Model\AltEnd;
use TRegx\Analyze\Simplify\Model\AltStart;
use TRegx\Analyze\Simplify\Model\Model;
use TRegx\Analyze\Simplify\ModelList;

class LiteralAltJoiner
{
    /** @var Model[] */
    private $models;

    /** @var ModelList */
    private $result;

    /** @var ModelList */
    private $buff;

    /** @var AlternativeExploder */
    private $exploder;

    /** @var bool */
    private $buffing = false;

    public function __construct(array $models)
    {
        $this->models = $models;
        $this->result = new ModelList();
        $this->buff = new ModelList();
        $this->exploder = new AlternativeExploder();
    }

    public function join(): array
    {
        foreach ($this->models as $model) {
            if ($model instanceof AltStart) {
                $this->alternativeStart($model);
            } else if ($model instanceof AltEnd) {
                $this->alternativeEnd($model);
            } else {
                $this->literals($model);
            }
        }
        return $this->result->getAndClear();
    }

    private function alternativeStart(AltStart $alternative): void
    {
        if ($this->buffing) {
            $this->result->addAll($this->buff->getAndClear());
        } else {
            $this->buffing = true;
        }
        $this->buff->add($alternative);
    }

    private function alternativeEnd(AltEnd $alternative): void
    {
        if ($this->buffing) {
            $this->buff->add($alternative);
            $alt = $this->createAlt($this->buff->getAndClear());
            $this->result->add($alt);
            $this->buffing = false;
        } else {
            $this->result->add($alternative);
        }
    }

    private function createAlt(array $models): Alt
    {
        if (count($models) < 0) {
            throw new InternalException();
        }
        return new Alt($this->exploder->explode($this->stripAltModels($models)));
    }

    private function stripAltModels(array $models): array
    {
        return array_slice($models, 1, -1);
    }

    private function literals(Model $model): void
    {
        if ($this->buffing) {
            $this->buff->add($model);
        } else {
            $this->result->add($model);
        }
    }
}

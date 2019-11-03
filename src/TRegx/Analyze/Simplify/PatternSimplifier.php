<?php
namespace TRegx\Analyze\Simplify;

use TRegx\Analyze\Simplify\Alternative\AlternativeGrouper;
use TRegx\Analyze\Simplify\Posix\SetGrouper;
use TRegx\Analyze\Simplify\Model\Model;

class PatternSimplifier
{
    /** @var SetGrouper */
    private $setGrouper;

    /** @var AlternativeGrouper */
    private $alternativeGrouper;

    public function __construct(string $pattern)
    {
        $this->setGrouper = new SetGrouper(new QuotesBreaker($pattern), new ModelFactory());
        $this->alternativeGrouper = new AlternativeGrouper();
    }

    public function simplify(): string
    {
        $models = $this->setGrouper->getGrouped();
        $grouped = $this->alternativeGrouper->getGrouped($models);
        return join($this->getContent($grouped));
    }

    private function getContent(array $broken): array
    {
        return array_map(function (Model $model) {
            return $model->getContent();
        }, $broken);
    }
}

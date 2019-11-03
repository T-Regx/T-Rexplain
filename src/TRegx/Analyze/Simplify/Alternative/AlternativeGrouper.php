<?php
namespace TRegx\Analyze\Simplify\Alternative;

use TRegx\Analyze\Simplify\Model\Model;

class AlternativeGrouper
{
    /**
     * @param Model[] $models
     * @return Model[]
     */
    public function getGrouped(array $models): array
    {
        $split = (new LiteralAltSplitter($models))->split();
        return (new LiteralAltJoiner($split))->join();
    }
}

<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use SQLite3;

class GenerateSetsAction extends AbstractRenderAction
{
    protected ?string $subNamespace = 'Sets';

    public function query(): void
    {
        $this->results = $db->query(<<<HERE
SELECT
    name, block, code
FROM
    sets;
HERE
        );
    }
}
<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

class GenerateCardBorderColorAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardBorderColor';

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    borderColor
FROM
    cards
WHERE
    borderColor IS NOT NULL;
HERE
        );
    }
}
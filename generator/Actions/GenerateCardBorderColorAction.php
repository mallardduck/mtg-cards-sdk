<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

class GenerateCardBorderColorAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardBorderColor';

    public static function getEnumMainColumn(): string
    {
        return 'borderColor';
    }

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    {$this->getEnumMainColumn()}
FROM
    cards
WHERE
    {$this->getEnumMainColumn()} IS NOT NULL;
HERE
        );
    }
}
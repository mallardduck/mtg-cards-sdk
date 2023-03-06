<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\Parameter;
use SQLite3;
use SQLite3Result;
use function Symfony\Component\String\u;

class GenerateSetTypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'SetType';

    protected SQLite3Result $results;

    public function __construct(
        SQLite3 $db,
    ) {
        $this->results = $db->query('SELECT DISTINCT type FROM sets;');
    }

    public function __invoke() {
        $enumDetails = [];
        while ($row = $this->results->fetchArray()) {
            $enumDetails[] = [
                'name' => u($row[0])->camel()->title()->toString(),
                'label' => u($row[0])->replace('_', ' ')->title()->toString(),
                'value'=> $row[0],
            ];
        }
        usort(
            $enumDetails,
            static fn ($a, $b) => $a <=> $b,
        );
        $this->save($this->renderEnum($enumDetails));
    }
}
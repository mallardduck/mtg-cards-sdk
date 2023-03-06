<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Enums\Block;
use SQLite3;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see Block
 */
class GenerateBlocksAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'Block';

    protected SQLite3Result $results;

    public function __construct(
        SQLite3 $db,
    ) {
        $this->results = $db->query('SELECT DISTINCT block FROM sets;');
    }

    public function __invoke() {
        $enumDetails = [];
        while ($row = $this->results->fetchArray()) {
            if ($row[0] === null) continue;
            $enumDetails[] = [
                'name' => u($row[0])->camel()->title()->toString(),
                'label'=> $row[0],
                'value' => u($row[0])->snake()->toString(),
            ];
        }
        usort(
            $enumDetails,
            static fn ($a, $b) => $a <=> $b,
        );
        $this->save($this->renderEnum($enumDetails));
    }
}
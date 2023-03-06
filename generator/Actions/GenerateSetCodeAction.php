<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Enums\SetType;
use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;
use Nette\PhpGenerator\EnumType;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see SetType
 */
class GenerateSetCodeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'SetCode';

    public static function getEnumMainColumn(): string
    {
        return 'code';
    }

    public static function registerHooks(HooksEmitter $emitter): void
    {
        // This filter ensure we have the extra data for later rendering...
        $emitter->addFilter(
            Events::PreEnumFormat->eventSuffixedKey(static::class_basename(static::class)),
            function (array $data): array {
                return [
                    'name' => u(static::digitToEnglish($data['name']))->camel()->title()->toString(),
                    'label' => u($data[static::getEnumMainColumn()])->replace('_', ' ')->title()->toString(),
                    'value' => u($data[static::getEnumMainColumn()])->snake()->toString(),
                    'extra' => [
                        'full-name' => $data['name'],
                        'parentCode' => $data['parentCode'],
                    ],
                ];
            },
        );

        // This filter actually helps to render those extra fields
        $emitter->addAction(
            Events::PreEnumInserted->eventSuffixedKey(static::class_basename(static::class)),
            function (EnumType &$enum, array $data) {
                $mapped = array_merge(...array_map(fn($val) => [$val['value'] => $val['extra']['full-name']], $data));
                $enum->addConstant('CODE_NAME_MAP', $mapped);
                // Add getName method...
                $enum->addMethod('getName')
                    ->setReturnType('string')
                    ->addBody(<<<'NOW'
$value ??= $this->value;
if (!isset(SetCode::CODE_NAME_MAP[$value])) {
    throw new \RuntimeException('Invalid code...');
}
return SetCode::CODE_NAME_MAP[$value];
NOW
                    )
                    ->addParameter('value', null)
                    ->setType('null|string');
                // Add from Name...
                $enum->addMethod('fromName')
                    ->setReturnType('self')
                    ->addBody(<<<'NOW'
$nameCodes = array_flip(SetCode::CODE_NAME_MAP);
if (!isset($nameCodes[$name])) {
    throw new \RuntimeException('Invalid code...');
}
return SetCode::from($nameCodes[$name]);
NOW
                    )
                    ->addParameter('name')
                    ->setType('string');
            },
        );
    }

    public function query(): void
    {
        $this->results = $this->db->query('SELECT DISTINCT code, name, parentCode FROM sets;');
    }
}
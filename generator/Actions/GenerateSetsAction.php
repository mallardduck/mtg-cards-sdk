<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Foundation\AbstractSet;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;

class GenerateSetsAction extends AbstractRenderAction
{
    protected ?string $subNamespace = 'Sets';

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT
    name, block, code,
    isFoilOnly, isNonFoilOnly, isOnlineOnly, isPartialPreview,
    keyruneCode, mcmName, parentCode, releaseDate,
    baseSetSize, totalSetSize, type
FROM
    sets
ORDER BY 
    name;
HERE
        );
    }

    public function __invoke(): void {
        $namespace = new PhpNamespace(static::NAMESPACE_BASE);
        $setClassGen = new ClassType('Sets');
        $this->query();
        $enumDetails = [];
        while ($row = $this->getResultsRowArray()) {
            $this->rendersClass = str_replace([' ', ',', "'", ':', '.', '-', '&', '/', '(', ')', '’'], '', $this::digitToEnglish($row['name']));
            $setClassGen->addMethod(lcfirst($this->rendersClass))
                ->setStatic()
                ->setReturnType("MallardDuck\MtgCardsSdk\Sets\\" . $this->rendersClass)
                ->setBody(<<<HERE
return new AllSets\\{$this->rendersClass};
HERE
);
            $this->save($this->renderSetInstance($row));
        }
        $this->subNamespace = null;
        $this->rendersClass = 'Sets';
        $namespace->add($setClassGen)
            ->addUse("MallardDuck\MtgCardsSdk\Sets", 'AllSets');
        $file = new PhpFile;
        $file->setStrictTypes();
        $file->addNamespace($namespace);

        $this->save((string) $file);
    }

    private function castValue(string $type, ?string $value): mixed
    {
        if (str_starts_with($type, '?') && $value === null) return $value;
        if (str_starts_with($type, '?')) $type = substr($type, 1, strlen($type) -1);
        if (class_exists($type)) {
            if (enum_exists($type)) {
                // It's an enum...
                return $type::tryFrom($value) ?? $type::tryFromLabel($value);
            } else {
                // TODO: clean up....
                // It's a class...
                dd(
                    'figure out of this is needed',
                    $type,
                    $value,
                );
            }
        }

        if ($type === 'bool') return boolval($value);
        if ($type === 'int') return intval($value);

        // Return back a string...
        return $value;
    }

    private function renderSetInstance(array $row): string
    {
        $namespace = $this->getNamespace();
        $setClassGen = new ClassType($this->rendersClass);
        $constructor = $setClassGen->setExtends(AbstractSet::class)
            ->setFinal(true)
            ->addMethod('__construct')
            ->setBody(<<<'HERE'
        parent::__construct(
            $name,
            $block,
            $code,
            $isFoilOnly,
            $isNonFoilOnly,
            $isOnlineOnly,
            $isPartialPreview,
            $keyruneCode,
            $mcmName,
            $parentCode,
            $releaseDate,
            $baseSetSize,
            $totalSetSize,
            $type
        );
HERE
)
        ;

        $defaultParams = (new \ReflectionClass(AbstractSet::class))
            ->getMethod('__construct')
            ->getParameters();
        foreach ($defaultParams as $constructorParam) {
            if (class_exists($constructorParam->getType()->getName()) && enum_exists($constructorParam->getType()->getName())) {
                $namespace->addUse($constructorParam->getType()->getName());
            }
            $default = $this->castValue($constructorParam->getType(), $row[$constructorParam->name]);
            $constructor->addParameter($constructorParam->name, $default)
                ->setType($constructorParam->getType())
            ;
        }

        $namespace
            ->addUse(AbstractSet::class)
            ->add($setClassGen);

        $file = new PhpFile;
        $file->setStrictTypes();
        $file->addNamespace($namespace);

        return (new PsrPrinter)->printFile($file);
    }
}
<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use Nette\PhpGenerator\PhpNamespace;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractRenderAction
{
    const NAMESPACE_BASE = "MallardDuck\MtgCardsSdk";
    protected string $rendersClass = 'SetType';

    protected ?string $subNamespace = null;
    protected string $renderTo = __DIR__ . '/../../src/';

     /**
     * Get the class "basename" of the given object / class.
     */
    public function class_basename(string|object $class): string
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
    protected static function render(string $template, array $data): string
    {
        $loader = new FilesystemLoader(__DIR__.'/../templates');
        $twig = new Environment($loader, [
            'cache' => false,
            'autoescape' => false,
        ]);
        return $twig->load($template)->render($data);
    }

    protected function getNamespace(): PhpNamespace
    {
        if ($this->subNamespace !== null) {
            return new PhpNamespace(static::NAMESPACE_BASE . '\\' . $this->subNamespace);
        }
        return new PhpNamespace(static::NAMESPACE_BASE);
    }

    protected function save(string $code)
    {
        $renderPath = sprintf(
            '%s%s',
            $this->renderTo,
            str_replace('\\', '/', $this->subNamespace)
        );
        if (!is_dir($renderPath)) {
            mkdir($renderPath);
        }
        $renderFile = $renderPath . '/' . $this->rendersClass . '.php';
        file_put_contents($renderFile, sprintf(
            '<?php declare(strict_types=1);%1$s%1$s%2$s',
            PHP_EOL,
            $code
        ));
    }
}
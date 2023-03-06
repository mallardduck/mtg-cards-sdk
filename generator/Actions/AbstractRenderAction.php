<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use Nette\PhpGenerator\PhpNamespace;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractRenderAction
{
    const NAMESPACE_BASE = "MallardDuck\MtgCardsSdk";

    protected ?string $subNamespace = null;
    protected string $renderTo;
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
        if (!is_dir(dirname($this->renderTo))) {
            mkdir(dirname($this->renderTo));
        }
        file_put_contents($this->renderTo, sprintf(
            '<?php declare(strict_types=1);%1$s%1$s%2$s',
            PHP_EOL,
            $code
        ));
    }
}
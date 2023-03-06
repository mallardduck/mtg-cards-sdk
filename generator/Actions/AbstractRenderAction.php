<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;
use Nette\PhpGenerator\PhpNamespace;
use SQLite3;
use SQLite3Result;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function Symfony\Component\String\u;

abstract class AbstractRenderAction
{
    const NAMESPACE_BASE = "MallardDuck\MtgCardsSdk";
    protected string $rendersClass;

    protected ?string $subNamespace = null;
    protected string $renderTo = __DIR__ . '/../../src/';

    public SQLite3Result $results;

    public function __construct(
        public SQLite3 $db,
    ) {}

    public function __invoke(): void {
        $this->query();
    }

    /**
     * This must be implemented somewhere, and it needs to set the $results field.
     * @return void
     */
    abstract public function query(): void;

    protected function getResultsRowArray(): array|bool
    {
        return $this->results->fetchArray(SQLITE3_ASSOC);
    }

    public static function registerHooks(HooksEmitter $emitter): void
    {

    }

    public function getBasename(null|string|object $class = null): string
    {
        if ($class === null) {
            $class = $this;
        }
        return static::class_basename($class);
    }

     /**
     * Get the class "basename" of the given object / class.
     */
    public static function class_basename(string|object $class): string
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
            str_replace('\\', '/', $this->subNamespace ?? '')
        );
        if (!is_dir($renderPath)) {
            mkdir($renderPath);
        }
        $renderFile = $renderPath . '/' . $this->rendersClass . '.php';
        file_put_contents($renderFile, $code);
    }

    protected static function digitToEnglish($input) {
        if (!filter_var($input[0], FILTER_VALIDATE_INT)) {
            return $input;
        }

        $spelloutFormatter = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        $words = explode(' ', $input);
        preg_match_all('/\d+|[a-zA-Z]+/', $words[0], $matches);
        $result = array();
        foreach ($matches[0] as $match) {
            if (ctype_alpha($match)) {
                $result[] = $match;
            } else {
                $result[] = u($spelloutFormatter->format($match))->camel()->title()->toString();
            }
        }
        $words[0] = implode('', $result);

        return implode(' ', $words);
    }
}
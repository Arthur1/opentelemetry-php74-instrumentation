<?php

declare(strict_types=1);

namespace OpenTelemetryPHP74\Instrumentation\Laravel\Hooks;

use OpenTelemetry\API\Instrumentation\CachedInstrumentation;

trait LaravelHookTrait
{
    /** @var LaravelHook */
    private static $instance;

    /** @var CachedInstrumentation */
    protected $instrumentation;

    protected function __construct(CachedInstrumentation $instrumentation)
    {
        $this->instrumentation = $instrumentation;
    }

    abstract public function instrument(): void;

    /** @psalm-suppress PossiblyUnusedReturnValue */
    public static function hook(CachedInstrumentation $instrumentation): LaravelHook
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (!isset(self::$instance)) {
            /** @phan-suppress-next-line PhanTypeInstantiateTraitStaticOrSelf,PhanTypeMismatchPropertyReal */
            self::$instance = new self($instrumentation);
            self::$instance->instrument();
        }

        return self::$instance;
    }
}

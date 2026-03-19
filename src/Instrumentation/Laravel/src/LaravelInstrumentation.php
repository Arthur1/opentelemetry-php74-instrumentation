<?php

declare(strict_types=1);

namespace OpenTelemetryPHP74\Instrumentation\Laravel;

use OpenTelemetry\API\Instrumentation\CachedInstrumentation;
use OpenTelemetry\SDK\Common\Configuration\Configuration;

class LaravelInstrumentation
{
    public const NAME = 'laravel';

    public static function register(): void
    {
        $instrumentation = new CachedInstrumentation(
            'Arthur1/opentelemetry-php74/instrumentation-laravel',
            null
        );

        Hooks\Illuminate\Contracts\Http\Kernel::hook($instrumentation);
        Hooks\Illuminate\Database\Eloquent\Model::hook($instrumentation);
    }

    public static function shouldTraceCli(): bool
    {
        return PHP_SAPI !== 'cli' || (
            class_exists(Configuration::class)
            && Configuration::getBoolean('OTEL_PHP_TRACE_CLI_ENABLED', false)
        );
    }
}

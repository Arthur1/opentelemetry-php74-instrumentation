<?php

declare(strict_types=1);

/**
 * Based on code from open-telemetry/opentelemetry-php-contrib
 * Copyright 2019 opentelemetry-php-contrib contributors
 * Licensed under the Apache License, Version 2.0
 */

namespace OpenTelemetryPHP74\Instrumentation\Laravel\Propagators;

use function assert;
use OpenTelemetry\Context\Propagation\PropagationSetterInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
class ResponsePropagationSetter implements PropagationSetterInterface
{
    public static function instance(): self
    {
        static $instance;

        return $instance ??= new self();
    }

    public function set(&$carrier, string $key, string $value): void
    {
        assert($carrier instanceof Response);

        $carrier->headers->set($key, $value);
    }
}

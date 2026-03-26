# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is an experimental OpenTelemetry zero-code instrumentation framework for PHP 7.4. It consists of:
1. A native PHP C extension (`/ext/`) that hooks into the Zend engine via observers
2. PHP 7.4 auto-instrumentation libraries (`/src/`)
3. Example applications (`/examples/`)

## Building the Extension

```bash
cd ext/
phpize
./configure --enable-opentelemetry_php74
make -j$(nproc)
make install
```

Or via Docker:
```bash
docker build -t ext-opentelemetry_php74 ext/
```

## Running Tests

Tests are in PHPT format under `ext/tests/`. Run them inside Docker:

```bash
docker build -t ext-opentelemetry_php74 ext/
docker run --rm ext-opentelemetry_php74 make test TESTS=tests/ NO_INTERACTION=1         # full suite
docker run --rm ext-opentelemetry_php74 make test TESTS=tests/002.phpt NO_INTERACTION=1 # single test
```

## Architecture

### Extension Layer (`/ext/`)

The extension registers a single PHP function: `OpenTelemetryPHP74\Instrumentation\hook()`.

```php
hook(?string $class, string $function, ?Closure $pre, ?Closure $post): bool
```

- **`opentelemetry_php74.c`** — module entry point; registers the `hook()` function and module globals (`OTELPHP74_G`)
- **`otel_observer.c`** — implements Zend observer callbacks (`zend_observer_fcall_begin_handler` / `end_handler`) that fire the pre/post closures when hooked functions are called

The observer approach avoids `runkit`/`uopz` monkey-patching and works at the Zend VM level.

### Example Apps (`/examples/`)

Each subdirectory is a self-contained Docker Compose stack exporting traces via OTLP (e.g. to Jaeger).

```bash
cd examples/laravel8/
docker compose up
# Jaeger UI at http://localhost:16686
```

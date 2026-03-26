# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this directory.

## Overview

PHP 7.4 auto-instrumentation library for Laravel 6–8. Uses the `opentelemetry_php74` C extension (see `/ext/`) to register hooks that automatically create OpenTelemetry spans for Laravel framework operations.

## Architecture

- **`_register.php`** — auto-loaded by composer; checks extension availability and calls `LaravelInstrumentation::register()`
- **`LaravelInstrumentation.php`** — instantiates each hook class and calls `hook()` for each instrumented method
- **`src/Hooks/`** — one class per instrumented entry point:
  - `Illuminate/Contracts/Http/Kernel.php` — wraps `handle()` to create HTTP server spans
  - `Illuminate/Database/Eloquent/Model.php` — wraps Eloquent CRUD methods (`find`, `insert`, `update`, `delete`, …)
- **`LaravelHookTrait`** — singleton guard so each hook class registers itself only once
- **`PostHookTrait`** — common span-end logic (status, exceptions) shared across hook classes

## Hook Pattern

Pre-hook → creates span, stores it in context storage, returns (optionally modified) params.
Post-hook → retrieves span, sets result attributes or records exception, ends span.

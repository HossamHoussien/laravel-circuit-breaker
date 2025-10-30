<?php

declare(strict_types=1);

it('has default values for required config', function () {
    $config = config('circuit-breaker');

    $this->assertArrayHasKey('adapters', $config);
    $this->assertArrayHasKey('default', $config);
    $this->assertArrayHasKey('services', $config);
});

it('has the correct types', function () {
    $config = config('circuit-breaker');

    $this->assertIsArray($config['adapters']);
    $this->assertIsArray($config['default']);
    $this->assertIsArray($config['services']);
});

it('has a default driver defined in adapters', function () {
    $adapters = array_keys(config('circuit-breaker.adapters'));
    $defaultAdapter = config('circuit-breaker.default.adapter');

    $this->assertContains($defaultAdapter, $adapters);
});

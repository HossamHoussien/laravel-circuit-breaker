<?php

declare(strict_types=1);

namespace HossamHoussien\CircuitBreaker\Enums;

class Key
{
    public const OPENED = 'opened';

    public const HALF_OPENED = 'half_opened';

    public const FAILURES = 'failures';

    public const SUCCESSES = 'successes';
}

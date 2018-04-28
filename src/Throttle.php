<?php

namespace BCLib\AlmaPrinter;

/**
 * Throttles a process
 *
 * Forces execution to halt for a set duration
 *
 * @package BCLib\AlmaPrinter
 */
class Throttle
{
    private $throttle_duration;
    private $sleep_until;

    /**
     * @param float $throttle_duration duration to throttle
     */
    public function __construct(float $throttle_duration)
    {
        $this->throttle_duration = $throttle_duration;
        $this->resetSleepTimer();
    }

    public function throttle()
    {
        if (microtime(true) < $this->sleep_until) {
            time_sleep_until($this->sleep_until);
        }
        $this->resetSleepTimer();
    }

    private function resetSleepTimer(): void
    {
        $this->sleep_until = microtime(true) + $this->throttle_duration;
    }
}
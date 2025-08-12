<?php

namespace oihana\robots\enums;

use PHPUnit\Framework\TestCase;

class RobotsConfigTest extends TestCase
{
    public function testConstantsExistenceAndValues(): void
    {
        $this->assertTrue(defined(RobotsConfig::class . '::COMMAND'));
        $this->assertTrue(defined(RobotsConfig::class . '::ERRORS'));
        $this->assertTrue(defined(RobotsConfig::class . '::ROBOTS'));
        $this->assertTrue(defined(RobotsConfig::class . '::TIMEZONE'));

        $this->assertSame('command', RobotsConfig::COMMAND);
        $this->assertSame('errors', RobotsConfig::ERRORS);
        $this->assertSame('robots', RobotsConfig::ROBOTS);
        $this->assertSame('timezone', RobotsConfig::TIMEZONE);
    }
}
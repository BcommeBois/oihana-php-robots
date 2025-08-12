<?php

namespace oihana\robots\enums;

use PHPUnit\Framework\TestCase;

class RobotsDefinitionsTest extends TestCase
{
    public function testConstantsExistenceAndValues(): void
    {
        $this->assertTrue(defined(RobotsDefinition::class . '::APP_PATH'));
        $this->assertTrue(defined(RobotsDefinition::class . '::COMMAND'));
        $this->assertTrue(defined(RobotsDefinition::class . '::COMMANDS'));
        $this->assertTrue(defined(RobotsDefinition::class . '::CONFIG'));
        $this->assertTrue(defined(RobotsDefinition::class . '::CONFIG_PATH'));
        $this->assertTrue(defined(RobotsDefinition::class . '::ROBOTS'));

        $this->assertSame('appPath', RobotsDefinition::APP_PATH);
        $this->assertSame('command', RobotsDefinition::COMMAND);
        $this->assertSame('commands', RobotsDefinition::COMMANDS);
        $this->assertSame('config', RobotsDefinition::CONFIG);
        $this->assertSame('configPath', RobotsDefinition::CONFIG_PATH);
        $this->assertSame('robots', RobotsDefinition::ROBOTS);
    }
}
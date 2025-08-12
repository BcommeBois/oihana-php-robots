<?php

namespace oihana\robots\enums;

use PHPUnit\Framework\TestCase;

class RobotsActionTest extends TestCase
{
    public function testConstantsExistenceAndValues(): void
    {
        $this->assertTrue(defined(RobotsAction::class . '::CREATE'));
        $this->assertTrue(defined(RobotsAction::class . '::REMOVE'));

        $this->assertSame('create', RobotsAction::CREATE);
        $this->assertSame('remove', RobotsAction::REMOVE);
    }
}
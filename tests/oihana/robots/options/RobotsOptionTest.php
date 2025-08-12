<?php

declare(strict_types=1);

namespace oihana\robots\options;

use PHPUnit\Framework\TestCase;

use Symfony\Component\Console\Command\Command;

final class RobotsOptionTest extends TestCase
{
    public function testConstantFile()
    {
        $this->assertEquals(  RobotsOption::CONTENT     , 'content'     );
        $this->assertEquals(  RobotsOption::GROUP       , 'group'       );
        $this->assertEquals(  RobotsOption::OVERWRITE   , 'overwrite'   );
        $this->assertEquals(  RobotsOption::PATH        , 'path'   );
        $this->assertEquals(  RobotsOption::PERMISSIONS , 'permissions' );
    }

    public function testConstantsExist()
    {
        $expectedConstants =
        [
            'content',
            'path',
            'group',
            'overwrite',
            'owner',
            'permissions',
        ];

        $constants = RobotsOption::enums();

        foreach ( $expectedConstants as $const )
        {
            $this->assertContains($const, $constants);
        }
    }

    public function testConfigureAddsFileOptionWhenHasFileTrue()
    {
        $command = new Command('test');
        $command = RobotsOption::configure($command, true);

        $option = $command->getDefinition()->getOption('path');
        $this->assertTrue($option->isValueOptional());
    }

    public function testConfigureDoesNotAddFileOptionWhenHasFileFalse()
    {
        $command = new Command('test');
        $command = RobotsOption::configure($command, false);
        $this->assertFalse($command->getDefinition()->hasOption('path'));
    }

    public function testGetCommandOptionHyphenates()
    {
        $this->assertEquals('content', RobotsOption::getCommandOption('content'));
        $this->assertEquals('some-option', RobotsOption::getCommandOption('someOption'));
    }
}
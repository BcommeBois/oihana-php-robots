<?php

declare(strict_types=1);

namespace oihana\robots\options;

use PHPUnit\Framework\TestCase;

use Symfony\Component\Console\Command\Command;

final class RobotsOptionTest extends TestCase
{
    public function testConstantFile()
    {
        $this->assertEquals(  RobotsOption::FILE , 'file');
    }

    public function testConstantsExist()
    {
        $expectedConstants =
        [
            'content',
            'file',
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

        $option = $command->getDefinition()->getOption('file');
        $this->assertTrue($option->isValueOptional());
    }

    public function testConfigureDoesNotAddFileOptionWhenHasFileFalse()
    {
        $command = new Command('test');
        $command = RobotsOption::configure($command, false);
        $this->assertFalse($command->getDefinition()->hasOption('file'));
    }

    public function testGetCommandOptionHyphenates()
    {
        $this->assertEquals('content', RobotsOption::getCommandOption('content'));
        $this->assertEquals('some-option', RobotsOption::getCommandOption('someOption'));
    }
}
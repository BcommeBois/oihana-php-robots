<?php

declare(strict_types=1);

namespace oihana\robots\options;

use PHPUnit\Framework\TestCase;

final class RobotsOptionsTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $opts = new RobotsOptions();

        $this->assertFalse($opts->append, 'append should default to false');
        $this->assertNull($opts->content, 'content should default to null');
        $this->assertNull($opts->file, 'file should default to null');
        $this->assertTrue($opts->force, 'force should default to true');
        $this->assertNull($opts->group, 'group should default to null');
        $this->assertTrue($opts->lock, 'lock should default to true');
        $this->assertTrue($opts->overwrite, 'overwrite should default to true');
        $this->assertNull($opts->owner, 'owner should default to null');
        $this->assertNull($opts->permissions, 'permissions should default to null');
    }

    public function testConstructorInitialization(): void
    {
        $init = [
            'append'      => true,
            'content'     => 'User-agent: *',
            'file'        => '/robots.txt',
            'force'       => false,
            'group'       => 'www-data',
            'lock'        => false,
            'overwrite'   => false,
            'owner'       => 'root',
            'permissions' => 0644,
        ];

        $opts = new RobotsOptions($init);

        $this->assertTrue($opts->append);
        $this->assertSame('User-agent: *', $opts->content);
        $this->assertSame('/robots.txt', $opts->file);
        $this->assertFalse($opts->force);
        $this->assertSame('www-data', $opts->group);
        $this->assertFalse($opts->lock);
        $this->assertFalse($opts->overwrite);
        $this->assertSame('root', $opts->owner);
        $this->assertSame(0644, $opts->permissions);
    }

    public function testToStringReturnsFileOrEmpty(): void
    {
        $opts = new RobotsOptions();

        // By default file is null, so __toString returns empty string
        $this->assertSame('', (string) $opts);

        // When file is set, __toString returns it
        $opts->file = '/var/www/robots.txt';
        $this->assertSame('/var/www/robots.txt', (string) $opts);
    }
}
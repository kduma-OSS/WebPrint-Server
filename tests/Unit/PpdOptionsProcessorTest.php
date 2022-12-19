<?php

namespace Tests\Unit;

use App\PpdOptionsProcessor;
use PHPUnit\Framework\TestCase;

class PpdOptionsProcessorTest extends TestCase
{
    public const NEW_OPTIONS = [
        'Resolution' => [
            'key' => 'Resolution',
            'name' => 'Output Resolution',
            'values' => [
                '150dpi' => [
                    'key' => '150dpi',
                    'name' => '150 DPI',
                    'order' => 1,
                    'enabled' => true,
                ],
                '300dpi' => [
                    'key' => '300dpi',
                    'name' => '300 DPI',
                    'order' => 2,
                    'enabled' => true,
                ],
                '600dpi' => [
                    'key' => '600dpi',
                    'name' => '600 DPI',
                    'order' => 3,
                    'enabled' => true,
                ],
                '1200dpi' => [
                    'key' => '1200dpi',
                    'name' => '1200 DPI',
                    'order' => 4,
                    'enabled' => true,
                ],
                '2400dpi' => [
                    'key' => '2400dpi',
                    'name' => '2400 DPI',
                    'order' => 5,
                    'enabled' => true,
                ],
            ],
            'default' => '300dpi',
            'enabled' => true,
            'order' => 2,
            'group_key' => 'General',
            'group_name' => 'General',
        ],
        'PDFVer' => [
            'key' => 'PDFVer',
            'name' => 'PDF version',
            'values' => [
                '1.1' => [
                    'key' => '1.1',
                    'name' => '1.1',
                    'order' => 1,
                    'enabled' => true,
                ],
                '1.2' => [
                    'key' => '1.2',
                    'name' => '1.2',
                    'order' => 2,
                    'enabled' => true,
                ],
                '1.3' => [
                    'key' => '1.3',
                    'name' => '1.3',
                    'order' => 3,
                    'enabled' => true,
                ],
                '1.4' => [
                    'key' => '1.4',
                    'name' => '1.4',
                    'order' => 4,
                    'enabled' => true,
                ],
                '1.5' => [
                    'key' => '1.5',
                    'name' => '1.5',
                    'order' => 5,
                    'enabled' => true,
                ],
            ],
            'default' => '1.2',
            'enabled' => true,
            'order' => 3,
            'group_key' => 'General',
            'group_name' => 'General',
        ],
    ];

    public const OLD_OPTIONS = [
        'Resolution' => [
            'name' => 'Output Resolution',
            'values' => [
                '150dpi' => [
                    'name' => '150 DPI',
                    'order' => 1,
                    'enabled' => true,
                ],
                '300dpi' => [
                    'name' => '300 DPI',
                    'order' => 2,
                    'enabled' => true,
                ],
                '600dpi' => [
                    'name' => '600 DPI',
                    'order' => 3,
                    'enabled' => true,
                ],
                '1200dpi' => [
                    'name' => '1200 DPI',
                    'order' => 4,
                    'enabled' => true,
                ],
                '2400dpi' => [
                    'name' => '2400 DPI',
                    'order' => 5,
                    'enabled' => true,
                ],
            ],
            'default' => '300dpi',
            'enabled' => true,
            'order' => 2,
            'group_key' => 'General',
            'group_name' => 'General',
        ],
        'PDFVer' => [
            'name' => 'PDF version',
            'values' => [
                '1.1' => [
                    'name' => '1.1',
                    'order' => 1,
                    'enabled' => true,
                ],
                '1.2' => [
                    'name' => '1.2',
                    'order' => 2,
                    'enabled' => true,
                ],
                '1.3' => [
                    'name' => '1.3',
                    'order' => 3,
                    'enabled' => true,
                ],
                '1.4' => [
                    'name' => '1.4',
                    'order' => 4,
                    'enabled' => true,
                ],
                '1.5' => [
                    'name' => '1.5',
                    'order' => 5,
                    'enabled' => true,
                ],
            ],
            'default' => '1.2',
            'enabled' => true,
            'order' => 3,
            'group_key' => 'General',
            'group_name' => 'General',
        ],
    ];

    public function test_validator()
    {
        $sut = new PpdOptionsProcessor();

        $result = $sut->isValid(self::NEW_OPTIONS);

        $this->assertTrue($result);

        $result = $sut->isValid(self::OLD_OPTIONS);

        $this->assertFalse($result);
    }

    public function test_upgrade()
    {
        $sut = new PpdOptionsProcessor();

        $result = $sut->upgrade(self::OLD_OPTIONS);

        $this->assertEquals(self::NEW_OPTIONS, $result);

        $result = $sut->isValid($result);

        $this->assertTrue($result);
    }
}

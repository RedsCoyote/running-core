<?php

namespace Running\tests\Core\Config;

use Running\Core\Config;
use Running\Fs\PhpFile;

class ConfigSaveTest extends \PHPUnit_Framework_TestCase
{

    const TMP_PATH = __DIR__ . '/tmp';

    protected function setUp()
    {
        mkdir(self::TMP_PATH);
        file_put_contents(self::TMP_PATH . '/savetest.config.php', <<<'CONFIG'
<?php

return [
    'application' => [
        'name' => 'Test Application'
    ],
];
CONFIG
        );
    }

    public function testSave()
    {
        $config = new Config(new PhpFile(self::TMP_PATH . '/savetest.config.php'));
        $this->assertEquals('Test Application', $config->application->name);

        $config->foo = 'bar';
        $config->baz = [1, 2, 3];
        $config->songs = ['Hey' => 'Jude', 'I just' => ['call' => ['to' => 'say']]];

        $config->save();

        $expectedText = <<<'CONFIG'
<?php

return [
  'application' =>
  [
    'name' => 'Test Application',
  ],
  'foo' => 'bar',
  'baz' =>
  [
    0 => 1,
    1 => 2,
    2 => 3,
  ],
  'songs' =>
  [
    'Hey' => 'Jude',
    'I just' =>
    [
      'call' =>
      [
        'to' => 'say',
      ],
    ],
  ],
];
CONFIG;
        $this->assertEquals(
            str_replace("\r\n", "\n", $expectedText),
            str_replace("\r\n", "\n", file_get_contents(self::TMP_PATH . '/savetest.config.php'))
        );
    }

    protected function tearDown()
    {
        unlink(self::TMP_PATH . '/savetest.config.php');
        rmdir(self::TMP_PATH);
    }

} 
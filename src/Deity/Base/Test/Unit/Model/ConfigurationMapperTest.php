<?php
namespace Deity\Base\Test\Unit\Model;

use Deity\Base\Model\ConfigurationMapper;
use Deity\Base\Model\Data\InputConfig;
use Magento\Framework\App\Config;
use Magento\Framework\Exception\CouldNotSaveException;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class ConfigurationMapperTest extends TestCase
{
    /**
     * @var ConfigurationMapper
     */
    private $falconConfigurationMapper;

    /**
     * @var Config
     */
    private $scopeConfig;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->scopeConfig = $this->createPartialMock(
            Config::class,
            ['getValue']
        );

        $this->falconConfigurationMapper = $this->objectManager->getObject(
            ConfigurationMapper::class,
            ['scopeConfig' => $this->scopeConfig]
        );
    }

    public function testProcessConfigurationInputExceptionIsThrown()
    {
        $inputData[] = new InputConfig('test', 'testValue');
        $inputData[] = new InputConfig('test2', 'testValue2');
        $this->expectException(CouldNotSaveException::class);
        $this->falconConfigurationMapper->processConfigurationInput($inputData);
    }

    public function testMultipleErrorsGettingThrown()
    {
        $inputData[] = new InputConfig('test', 'testValue');
        $inputData[] = new InputConfig('test2', 'testValue2');
        try {
            $this->falconConfigurationMapper->processConfigurationInput($inputData);
        } catch (CouldNotSaveException $e) {
            $errors = $e->getErrors();
            $this->assertEquals(2, count($errors), 'There should be at least 2 errors provided');
        }
    }

    public function testProcessConfigurationInput()
    {
        $inputData[] = new InputConfig('test', 'testValue');

        $this->scopeConfig
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue('some/path'));

        $values = $this->falconConfigurationMapper->processConfigurationInput($inputData);

        $this->assertTrue(array_key_exists('some/path', $values), "Config value should be mapped");

        $this->assertEquals($values['some/path'], 'testValue', "Config value should be preserved");
    }
}

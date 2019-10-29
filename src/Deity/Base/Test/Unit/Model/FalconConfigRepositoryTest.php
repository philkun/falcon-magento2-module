<?php

namespace Deity\Base\Test\Unit\Model;

use Deity\Base\Model\ConfigurationMapper;
use Deity\Base\Model\Data\InputConfig;
use Deity\Base\Model\FalconConfigRepository;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class FalconConfigRepositoryTest extends TestCase
{

    /**
     * @var FalconConfigRepository
     */
    private $falconConfigRepository;

    /**
     * @var ConfigurationMapper
     */
    private $falconConfigurationMapper;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->falconConfigurationMapper = $this->createPartialMock(
            ConfigurationMapper::class,
            ['processConfigurationInput']
        );

        $this->falconConfigRepository = $this->objectManager->getObject(
            FalconConfigRepository::class,
            ['configurationMapper' => $this->falconConfigurationMapper]
        );
    }

    public function testSaveConfigurationWithInvalidKeys()
    {
        $inputData[] = new InputConfig('test', 'testValue');
        $inputData[] = new InputConfig('test2', 'testValue2');

        $result = $this->falconConfigRepository->saveConfiguration($inputData);

        $this->assertEquals(false, $result, "Repository should fail on invalid test params");
    }

    public function testSaveConfiguration()
    {
        $inputData[] = new InputConfig('url', 'https://falcon.local/');

        $this->falconConfigurationMapper
            ->expects($this->any())
            ->method('processConfigurationInput')
            ->will($this->returnValue(['path/to/magento' => 'https://falcon.local/']));

        $result = $this->falconConfigRepository->saveConfiguration($inputData);

        $this->assertEquals(true, $result, "Repository should successfully save the configuration");
    }
}

<?php
declare(strict_types=1);

namespace Deity\FalconCache\Model;

use Deity\FalconCacheApi\Model\ConfigProviderInterface;
use Deity\FalconCacheApi\Model\FalconApiAdapterInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class FalconApiAdapter
 *
 * @package Deity\FalconCache\Model
 */
class FalconApiAdapter implements FalconApiAdapterInterface
{
    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * @var SerializerInterface
     */
    private $jsonEncode;

    /**
     * @var string
     */
    private $error = '';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * FalconApiAdapter constructor.
     * @param ClientFactory $clientFactory
     * @param SerializerInterface $json
     * @param LoggerInterface $logger
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        ClientFactory $clientFactory,
        SerializerInterface $json,
        LoggerInterface $logger,
        ConfigProviderInterface $configProvider
    ) {
        $this->configProvider = $configProvider;
        $this->clientFactory = $clientFactory;
        $this->jsonEncode = $json;
        $this->logger = $logger;
    }

    /**
     * Get Client object
     *
     * @param array $params
     * @return bool
     */
    private function makeRequest(array $params): bool
    {
        try {
            $falconApiUrl = $this->configProvider->getFalconApiCacheUrl();
            if ($falconApiUrl === '') {
                $this->error = 'Falcon Cache API Url is not set.';
                return false;
            }
            /** @var Curl $curlClient */
            $curlClient = $this->clientFactory->create();
            $curlClient->addHeader('Content-Type', 'application/json');
            $curlClient->post($falconApiUrl, $this->jsonEncode->serialize($params));
            if ($curlClient->getStatus() === 200) {
                return true;
            }
            $this->logger->error($curlClient->getBody());
            $this->error = $curlClient->getBody();
        } catch (\Exception $e) {
            $this->logger->error('Falcon cache error: ' . $e->getMessage());
            $this->error = $e->getMessage();
        }

        return false;
    }

    /**
     * Flush the cache storage of falcon
     *
     * @param string $entityTypeCode
     * @return bool
     */
    public function flushCacheForGivenType(string $entityTypeCode): bool
    {
        $bodyRequest= [['type' => $entityTypeCode]];
        return $this->makeRequest($bodyRequest);
    }

    /**
     * Flush cache for given entities data
     *
     * @param array $bodyRequest
     * @return bool
     */
    public function flushCacheForEntities(array $bodyRequest): bool
    {
        if (empty($bodyRequest)) {
            return false;
        }

        return $this->makeRequest($bodyRequest);
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }
}

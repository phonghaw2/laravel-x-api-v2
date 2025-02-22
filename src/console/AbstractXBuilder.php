<?php
namespace Phonghaw2\X\Console;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use GuzzleHttp\HandlerStack;
use Phonghaw2\X\Exceptions\InvalidConfiguration;

abstract class AbstractXBuilder
{

    public ParameterValidator $validator;

    /** @var int $accountId OAuth1 User ID */
    protected int $accountId;

    /** @var string */
    private string $accessToken;

    /** @var string */
    private string $accessTokenSecret;

    /** @var string */
    private string $consumerKey;

    /** @var string */
    private string $consumerSecret;

    /** @var string */
    private string $bearerToken;

    /** @var string $mode mode of operation */
    private string $httpRequestMethod = 'GET';

    /** @var int $authMode API Auth Mode (0: Bearer token; 1: OAuth1 token) */
    protected int $authMode = 0;

    /** @var string $endpoint */
    private string $endpoint = '';

    /** @var string $queryString */
    protected array $queryString = '';

    /** @var array<mixed> $queryParams */
    protected array $queryParams = [];

    /** @var array<string|int> $postData */
    protected array $postData = [];

    /**
     * Creates object. Requires an array of settings.
     * @param array<string> $settings
     * @throws \Exception when CURL extension is not loaded
     */
    public function __construct(array $settings)
    {
        $this->extensionLoaded('curl');
        $this->extensionLoaded('json');
        $this->parseSettings($settings);
    }

    /**
     * Perform the request to X API
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException|\RuntimeException|\JsonException
     */
    public function sendRequest()
    {
        try {
            $apiBaseUri = config('twitter.api_base_url');
            if ($apiBaseUri == '') {
                throw InvalidConfiguration::endpointNotValid();
            }

            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ];

            switch ($this->authMode) {
                case 0:
                    // Inject the Bearer token header
                    $client = new Client(['base_uri' => $apiBaseUri]);
                    $headers['Authorization'] = 'Bearer ' . $this->bearerToken;
                    break;
                case 1:
                    // Insert Oauth1 middleware
                    $stack = HandlerStack::create();
                    $middleware = new Oauth1([
                        'consumer_key'    => $this->consumerKey,
                        'consumer_secret' => $this->consumerSecret,
                        'token'           => $this->accessToken,
                        'token_secret'    => $this->accessTokenSecret,
                    ]);
                    $stack->push($middleware);
                    $client = new Client([
                        'base_uri'  => $apiBaseUri,
                        'handler'   => $stack,
                        'auth'      => 'oauth'
                    ]);
                default:
                    throw InvalidConfiguration::authenticationMethodNotSupported();
                    break;
            }

            $response  = $client->request($this->httpRequestMethod, $this->getEndPoint(), [
                'verify' => !$this->is_windows(),
                'headers' => $headers,
                // This is always array from function spec
                // https://docs.guzzlephp.org/en/stable/request-options.html#body
                'json' => count($this->postData) ? $this->postData : null,
            ]);

            $body = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
            if ($response->getStatusCode() >= 400) {
                $error = new \stdClass();
                $error->message = 'cURL error';
                if ($body) {
                    $error->details = $response;
                }
                throw new \RuntimeException(
                    json_encode($error, JSON_THROW_ON_ERROR),
                    $response->getStatusCode()
                );
            }
            return $body;

        } catch (ServerException $e) {
            $payload = json_decode(str_replace("\n", "", $e->getResponse()->getBody()->getContents()), false, 512, JSON_THROW_ON_ERROR);
            throw new \RuntimeException($payload->detail, $payload->status);
        }
    }

    private function is_windows(): bool {
        return DIRECTORY_SEPARATOR === '\\';
    }

    /**
     * Set Auth-Mode
     * @param int $value (0: Bearer token; 1: OAuth1 token)
     * @return void
     */
    public function setAuthMode(int $value): void
    {
        $this->authMode = $value;
    }

    /**
     * @throws \Exception
     */
    private function extensionLoaded(string $ext): void
    {
        if (!extension_loaded($ext)) {
            throw new \Exception('PHP extension ' . strtoupper($ext) . ' is not loaded.');
        }
    }

    /**
     * @param array<string> $settings
     * @return void
     * @throws \Exception
     */
    private function parseSettings(array $settings): void
    {
        if (!isset(
            // Account ID
            $settings['account_id'],
            // Consumer Keys
            $settings['api_key'],
            $settings['api_key_secret'],
            // Authentication Tokens
            $settings['bearer_token'],
            $settings['access_token'],
            $settings['access_token_secret']
        )) {
            throw new \Exception('Incomplete settings passed.');
        }

        $this->accountId            = (int) $settings['account_id'];
        $this->consumerKey          = $settings['api_key'];
        $this->consumerSecret       = $settings['api_key_secret'];
        $this->bearerToken          = $settings['bearer_token'];
        $this->accessToken          = $settings['access_token'];
        $this->accessTokenSecret    = $settings['access_token_secret'];
    }

    /**
     * Set postData value
     * @param string $postData
     * @return void
     */
    protected function setPostData(array $postData): void
    {
        $this->postData = $postData;
    }

    /**
     * Set Endpoint value
     * @param string $endpoint
     * @return void
     */
    protected function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Get Endpoint value with all query string
     * @return string
     */
    protected function getEndPoint(): string
    {
        $endpoint = $this->endpoint;
        if (!empty($this->queryString) > 0) {
            $endpoint .= '?' . $this->queryString;
        }
        return $endpoint;
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return AbstractController
     */
    protected function setQueryParams(array $queryParams): AbstractXBuilder
    {
        $this->queryString = http_build_query($queryParams);
        return $this;
    }

    /**
     * Set HTTP Request Method
     * @param string $value
     * @return void
     */
    protected function setHttpRequestMethod(string $value): void
    {
        if (in_array($value, ['GET', 'POST', 'PUT', 'DELETE'])) {
            $this->httpRequestMethod = $value;
        }
    }
}

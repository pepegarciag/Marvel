<?php

namespace App\Marvel;

use App\Marvel\Contracts\MarvelContract;
use GuzzleHttp\Client;


/**
 * Class Marvel
 */
class Marvel implements MarvelContract
{

    /**
     * The GuzzleHttp client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Marvel public key.
     *
     * @var string
     */
    protected $publicKey;

    /**
     * Marvel private key.
     *
     * @var mixed
     */
    protected $privateKey;

    /**
     * Marvel base uri.
     *
     * @var string
     */
    protected $baseUri;

    /**
     * Endpoint to request against Marvel api.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Params to be used in requests.
     *
     * @var array
     */
    protected $params = [];

    /**
     * Marvel constructor.
     *
     * @param array $config
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(array $config, Client $client)
    {
        $this->guzzle = $client;
        $this->publicKey = $config['public'];
        $this->privateKey = $config['private'];
        $this->baseUri = "{$config['base']}/{$config['version']}/public/";
    }

    /**
     * Set the full endpoint
     * .
     * @param $request
     * @return $this
     */
    protected function setEndpoint($request)
    {
      $this->endpoint = $this->baseUri . $request;
      return $this;
    }

    protected function getEndpoint() {
      return $this->endpoint;
    }

    /**
     * Bound params to a request.
     *
     * @param $params
     * @return $this
     */
    protected function with($params)
    {
      $this->params = $params;
      return $this;
    }

    /**
     * Perform the request.
     *
     * @return mixed
     */
    protected function request()
    {
        $defaultParams = $this->defaultParams();
        $params = $defaultParams->merge($this->params);

        $response = $this->guzzle->get($this->endpoint, [
          'query' => $params->toArray(),
        ]);

        $response = json_decode($response->getBody(), true);

        $this->params = [];

        return $response;
    }

    /**
     * Get the current timestamp.
     *
     * @return int
     */
    protected function getTimestamp()
    {
        return time();
    }

    /**
     * Get the needed hash for the request.
     *
     * @param $timestamp
     * @return string
     */
    protected function getHash($timestamp)
    {
        return md5($timestamp . $this->privateKey . $this->publicKey);
    }

    /**
     * Get the default params.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function defaultParams()
    {
      $timestamp = $this->getTimestamp();

      return collect([
        'apikey' => $this->publicKey,
        'ts' => $timestamp,
        'hash' => $this->getHash($timestamp),
        'limit' => 100,
        'offset' => 0,
      ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCharacterByName(string $name)
    {
      return $this->setEndpoint("characters")->with(['name' => $name])->request();
    }

    /**
     * {@inheritdoc}
     */
    public function getCharacterComics(int $characterId, $offset = 0, $limit = 4) {
      return $this->setEndpoint("characters/{$characterId}/comics")->with([
        'offset' => $offset,
        'limit' => $limit
      ])->request();
    }
}
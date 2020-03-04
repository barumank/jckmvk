<?php

namespace Backend\Library\Parsers\Teplo3000\Parser;

use Backend\Library\Parsers\Teplo3000\Parser\TDO\ResponseUrl;
use Backend\Library\Phalcon\Logger\TcpLogger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Phalcon\Logger\Adapter as LoggerAdapter;

class Loader
{
    private $client;
    /** @var TcpLogger*/
    private $logger;
    private $concurrency = 10;
    private $pullSize = 100;

    public function __construct(LoggerAdapter $logger)
    {
        $this->client = new Client([
            'base_uri' => 'https://teplo3000.ru',
        ]);
        $this->logger = $logger;
    }

    public function getUrl(string $url): ?string
    {
        $out = '';
        try {
            $response = $this->client->get($url);
            $out = $response->getBody()->getContents();
        } catch (\Exception $exception) {
            $message = "Ошибка get запроса: {$exception->getMessage()}";
            $this->logger->error($message);
        }
        return $out;
    }

    public function postUrl(string $url, array $params)
    {
        $out = '';
        try {
            $response = $this->client->post($url, [
                'form_params' => $params
            ]);
            $out = $response->getBody()->getContents();
        } catch (\Exception $exception) {
            $message = "Ошибка post запроса: {$exception->getMessage()}";
            $this->logger->error($message);
        }
        return $out;
    }

    public function getDataFromUrlGenerator(\Generator $urlGenerator): \Generator
    {
        $i = 0;
        $urlSet = [];
        foreach ($urlGenerator as $url) {
            if ($i++ < $this->pullSize) {
                $urlSet[] = $url;
            } else {
                $urlSetGenerator = $this->getSetGenerator($urlSet);
                foreach ($urlSetGenerator as $item) {
                    yield $item;
                }
                $i = 0;
                $urlSet = [];
            }
        }
        if (!empty($urlSet)) {
            $urlSetGenerator = $this->getSetGenerator($urlSet);
            foreach ($urlSetGenerator as $item) {
                yield $item;
            }
        }
    }

    private function getSetGenerator($urlSet): \Generator
    {
        $requestGenerator = (function ($urlSet) {
            foreach ($urlSet as $url) {
                yield new Request('GET', $url);
            }
        })($urlSet);
        $responseList = [];
        $pool = new Pool($this->client, $requestGenerator, [
            'concurrency' => $this->concurrency,
            'fulfilled' => function (Response $response, $index) use (&$responseList,&$urlSet) {
                $responseList[] = new ResponseUrl($urlSet[$index],$response->getBody()->getContents());
            },
            'rejected' => function (RequestException $reason, $index) {
                $message = "Ошибка get запроса: {$reason->getMessage()}";
                $this->logger->error($message);
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        foreach ($responseList as $item) {
            yield $item;
        }
    }


}

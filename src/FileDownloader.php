<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class FileDownloader
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function downloadFile($url, $savePath)
    {
        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',
                    'Referer' => 'https://www.alko.fi/valikoimat-ja-hinnasto/hinnasto',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                file_put_contents($savePath, $response->getBody());
                return "File downloaded successfully.";
            } else {
                return "Error: " . $response->getStatusCode();
            }
        } catch (RequestException $error) {
            return "Request failed: " . $error->getMessage();
        }
    }
}

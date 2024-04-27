<?php
namespace App\Service;

use GuzzleHttp\Client;

class BadWordChecker
{
    private $apiUrl;
    private $userId;
    private $apiKey;

    public function __construct(string $apiUrl, string $userId, string $apiKey)
    {
        $this->apiUrl = $apiUrl;
        $this->userId = $userId;
        $this->apiKey = $apiKey;
    }

    public function isCommentValid(string $commentaire): bool
    {
        $client = new Client();
        $response = $client->request('POST', $this->apiUrl, [
            'form_params' => [
                'user-id' => $this->userId,
                'api-key' => $this->apiKey,
                'contenuc' => $commentaire,
                'censor-character' => '*',
                'output-case' => 'camel'
            ]
        ]);
        $result = json_decode($response->getBody()->getContents(), true);

        // Check if comment is valid
        return !$result['is-bad'];
    }
}

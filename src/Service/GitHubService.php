<?php

namespace App\Service;

use App\Enum\HealthStatus;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubService
{
    public function __construct(private readonly HttpClientInterface $httpClient, private readonly LoggerInterface $logger)
    {
    }


    /**
     * @param string $dinosaurName
     * @return HealthStatus
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getHealthReport(string $dinosaurName): HealthStatus
    {
        //FIX: Variable '$health' is probably undefined
        $health = HealthStatus::HEALTHY;
        //composer require symfony/http-client
//        $client = HttpClient::create();
//
//        // Call GitHub API
//        //2 thing: First method type (in this case is GET) and Second URL
//        $response =  $client->request(
//            method: 'GET',
//            url: 'https://api.github.com/repos/SymfonyCasts/dino-park/issues'
//        );
        //Now using dependency injection because we always make real http request in our test
        $response = $this->httpClient->request(
            method: 'GET',
            url: 'https://api.github.com/repos/SymfonyCasts/dino-park/issues'
        );

        $this->logger->info('Request Dino Issues', [
            'dino' => $dinosaurName,
            'responseStatus' => $response->getStatusCode()
        ]);

        // Filter the issues
        foreach ($response->toArray() as $issue) {
            if(str_contains($issue['title'], $dinosaurName)) {
                //Do something
                $health = $this->getDinoStatusFromLabels($issue['labels']);
            }
        }

        return $health;
    }

    private function getDinoStatusFromLabels(array $labels): HealthStatus
    {
        $health = null;
        foreach ($labels as $label) {
            $label = $label['name'];
            // We only care about "Status" labels
            if (!str_starts_with($label, 'Status:')) {
                continue;

            }
            // Remove the "Status:" and whitespace from the label
            $status = trim(substr($label, strlen('Status:')));
            $health = HealthStatus::tryFrom($status);
            // Determine if we know about the label - throw an exception if we don't
            if (null === $health) {
                throw new \RuntimeException(sprintf('%s is an unknown status label!', $label));
            }
        }
        return $health ?? HealthStatus::HEALTHY;
    }
}
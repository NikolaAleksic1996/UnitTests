<?php

namespace App\Tests\Unit\Service;

use App\Enum\HealthStatus;
use App\Service\GitHubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GithubServiceTest extends TestCase
{
    private LoggerInterface $mockLogger;
    private MockHttpClient $mockHttpClient;
    private MockResponse $mockResponse;

    protected function setUp(): void
    {
        $this->mockLogger = $this->createMock(LoggerInterface::class);
        $this->mockHttpClient = new MockHttpClient();
    }

    /** @dataProvider dinoNameProvider
     * @param HealthStatus $expectedStatus
     * @param string $dinoName
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetHealthReportReturnsCorrectHealthStatusForDino(HealthStatus $expectedStatus, string $dinoName): void
    {
//        //MOCK allows us to pass in a class or interface and get back a "fake" instance of that class or interface.
//        // This object is called a mock.
//        $mockLogger = $this->createMock(LoggerInterface::class);
//        $mockHttpClient = $this->createMock(HttpClientInterface::class);
//        $mockResponse = $this->createMock(ResponseInterface::class);
//
//        //now we need teach how should like response object and how should like request because PHPUnit ignore
//        // method implementation from mock class 'objects'
//        $mockResponse
//            ->method('toArray')
//            ->willReturn([
//                [
//                    'title' => 'Daisy',
//                    'labels' => [['name' => 'Status: Sick']]
//                ],
//                [
//                'title' => 'Maverick',
//                'labels' => [['name' => 'Status: Healthy']]
//                ]
//            ]);
//
//        $mockHttpClient
//            ->expects(self::once())
//            ->method('request')
//            ->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues')
//            ->willReturn($mockResponse);
//
//        $service = new GitHubService($mockHttpClient, $mockLogger);


        $service = $this->createGithubService([
            [
                'title' => 'Daisy',
                'labels' => [['name' => 'Status: Sick']],
            ],
            [
                'title' => 'Maverick',
                'labels' => [['name' => 'Status: Healthy']],
            ],
        ]);

        self::assertSame($expectedStatus, $service->getHealthReport($dinoName));
        self::assertSame(1, $this->mockHttpClient->getRequestsCount());
        self::assertSame('GET', $this->mockResponse->getRequestMethod());
        self::assertSame('https://api.github.com/repos/SymfonyCasts/dino-park/issues', $this->mockResponse->getRequestUrl());
    }

    public function dinoNameProvider(): \Generator
    {
        yield 'Sick Dino' => [
            HealthStatus::SICK,
            'Daisy'
        ];
        yield 'Healthy Dino' => [
            HealthStatus::HEALTHY,
            'Maverick'
        ];
    }

    /**
    * @return void
    * @throws ClientExceptionInterface
    * @throws DecodingExceptionInterface
    * @throws RedirectionExceptionInterface
    * @throws ServerExceptionInterface
    * @throws TransportExceptionInterface
    */
    public function testExceptionThrownWithUnknownLabel(): void
    {
        //WORK WITH createMock
//        $mockLogger = $this->createMock(LoggerInterface::class);
//        $mockHttpClient = $this->createMock(HttpClientInterface::class);
//        $mockResponse = $this->createMock(ResponseInterface::class);
//
//        $mockResponse
//            ->method('toArray')
//            ->willReturn([
//                [
//                    'title' => 'Maverick',
//                    'labels' => [['name' => 'Status: Drowsy']],
//                ]
//            ]);
        //        $mockHttpClient
//            ->expects(self::once())
//            ->method('request')
//            ->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues')
//            ->willReturn($mockResponse);
//        $service = new GitHubService($mockHttpClient, $mockLogger);

        //WORK WITH Mock functions and benefits is small lines of code and....
//        $mockResponse = new MockResponse(json_encode([
//            [
//                    'title' => 'Maverick',
//                    'labels' => [['name' => 'Status: Drowsy']],
//                ]
//        ]));
//
//        $mockHttpClient = new MockHttpClient($mockResponse);
//
//        $service = new GitHubService($mockHttpClient, $this->createMock(LoggerInterface::class));


        //WORK with setUp and Tearing test
        $service = $this->createGithubService([
            [
                    'title' => 'Maverick',
                    'labels' => [['name' => 'Status: Drowsy']],
                ]
        ]);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Drowsy is an unknown status label!');

        $service->getHealthReport('Maverick');
    }

    public function createGithubService(array $responseData): GitHubService
    {
        $this->mockResponse = new MockResponse(json_encode($responseData));

        $this->mockHttpClient->setResponseFactory($this->mockResponse);

        return new GithubService($this->mockHttpClient, $this->mockLogger);
    }
}
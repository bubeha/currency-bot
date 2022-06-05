<?php

declare(strict_types=1);

namespace Tests\UI\Http\Rest\Response;

use UI\Http\Rest\Response\OpenApi;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
final class OpenApiTest extends TestCase
{
    /**
     * @group unit
     * @throws \JsonException
     */
    public function testFromPayload(): void
    {
        $response = OpenApi::fromPayload('Hello World!!!', Response::HTTP_OK);

        $content = $response->getContent();

        if (false === $content) {
            self::fail('Content is false');
        }

        /** @var string */
        $decode = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('Hello World!!!', $decode);
    }
}

<?php

declare(strict_types=1);

namespace Freeze\Framework\Test;

use Freeze\Framework\Kernel\Factory\ResponseFactory;
use Freeze\Framework\Kernel\Factory\StreamFactory;
use Http\Psr7Test\ResponseIntegrationTest;
use Psr\Http\Message\ResponseInterface;

final class ResponseTest extends ResponseIntegrationTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        \define('STREAM_FACTORY', StreamFactory::class);
    }

    public function createSubject(): ResponseInterface
    {
        return (new ResponseFactory(new StreamFactory()))->createResponse();
    }
}

<?php

/*****************************************************************************
 *
 *  PROJECT:     MTA PHP SDK
 *  LICENSE:     See LICENSE in the top level directory
 *  FILE:        RequestFactory.php
 *  VERSION:     1.0.0
 *
 *  Multi Theft Auto is available from http://www.multitheftauto.com/
 *
 *****************************************************************************/

declare(strict_types=1);

namespace MultiTheftAuto\Sdk\Factory;

use Http\Message\MessageFactory;
use InvalidArgumentException;
use MultiTheftAuto\Sdk\Authentication\Credential;
use Psr\Http\Message\RequestInterface;

class RequestFactory
{
    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var string
     */
    protected $httpMethod;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var Credential
     */
    protected $credential;

    protected function __construct(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;
    }

    public static function useMessageFactory(MessageFactory $messageFactory): RequestFactory
    {
        return new RequestFactory($messageFactory);
    }

    public function setMethod(string $httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function authenticate(Credential $credential)
    {
        $this->credential = $credential;
    }

    public function withBody(string $body): void
    {
        $this->body = $body;
    }

    public function build(): RequestInterface
    {
        if (!$this->httpMethod ||
            !$this->uri ||
            !$this->body) {
            throw new InvalidArgumentException('Parameters missing for the request');
        }

        return $this->messageFactory->createRequest(
            $this->httpMethod,
            $this->uri,
            $this->buildParameters(),
            $this->body
        );
    }

    protected function buildParameters(): array
    {
        if (!empty($this->credential)) {
            throw new InvalidArgumentException('Credential was not set for this call');
        }

        return [
            'Content-type' => 'text/json; charset=UTF8',
            'auth' => [$this->credential->getUser(), $this->credential->getPassword()],
        ];
    }
}

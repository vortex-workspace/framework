<?php

namespace Stellar\Request\Traits;


use Stellar\Request\Enums\ServerKey;

trait Server
{
    public static function getRedirectStatus(): ?string
    {
        return $_SERVER[ServerKey::REDIRECT_STATUS->value] ?? null;
    }

    public static function getHttpHost(): ?string
    {
        return $_SERVER[ServerKey::HTTP_HOST->value] ?? null;
    }

    public static function getHttpUserAgent(): ?string
    {
        return $_SERVER[ServerKey::HTTP_USER_AGENT->value] ?? null;
    }

    public static function getHttpAccept(): ?string
    {
        return $_SERVER[ServerKey::HTTP_ACCEPT->value] ?? null;
    }

    public static function getHttpAcceptLanguage(): ?string
    {
        return $_SERVER[ServerKey::HTTP_ACCEPT_LANGUAGE->value] ?? null;
    }

    public static function getHttpAcceptEncoding(): ?string
    {
        return $_SERVER[ServerKey::HTTP_ACCEPT_ENCODING->value] ?? null;
    }

    public static function getHttpConnection(): ?string
    {
        return $_SERVER[ServerKey::HTTP_CONNECTION->value] ?? null;
    }

    public static function getHttpCookie(): ?string
    {
        return $_SERVER[ServerKey::HTTP_COOKIE->value] ?? null;
    }

    public static function getHttpUpgradeInsecureRequests(): ?string
    {
        return $_SERVER[ServerKey::HTTP_UPGRADE_INSECURE_REQUESTS->value] ?? null;
    }

    public static function getPath(): ?string
    {
        return $_SERVER[ServerKey::PATH->value] ?? null;
    }

    public static function getSystemRoot(): ?string
    {
        return $_SERVER[ServerKey::SYSTEM_ROOT->value] ?? null;
    }

    public static function getComSpec(): ?string
    {
        return $_SERVER[ServerKey::COMSPEC->value] ?? null;
    }

    public static function getPathExt(): ?string
    {
        return $_SERVER[ServerKey::PATHEXT->value] ?? null;
    }

    public static function getWinDir(): ?string
    {
        return $_SERVER[ServerKey::WINDIR->value] ?? null;
    }

    public static function getServerSignature(): ?string
    {
        return $_SERVER[ServerKey::SERVER_SIGNATURE->value] ?? null;
    }

    public static function getServerSoftware(): ?string
    {
        return $_SERVER[ServerKey::SERVER_SOFTWARE->value] ?? null;
    }

    public static function getServerName(): ?string
    {
        return $_SERVER[ServerKey::SERVER_NAME->value] ?? null;
    }

    public static function getServerAddr(): ?string
    {
        return $_SERVER[ServerKey::SERVER_ADDR->value] ?? null;
    }

    public static function getServerPort(): ?string
    {
        return $_SERVER[ServerKey::SERVER_PORT->value] ?? null;
    }

    public static function getRemoteAddr(): ?string
    {
        return $_SERVER[ServerKey::REMOTE_ADDR->value] ?? null;
    }

    public static function getDocumentRoot(): ?string
    {
        return $_SERVER[ServerKey::DOCUMENT_ROOT->value] ?? null;
    }

    public static function getRequestScheme(): ?string
    {
        return $_SERVER[ServerKey::REQUEST_SCHEME->value] ?? null;
    }

    public static function getContextPrefix(): ?string
    {
        return $_SERVER[ServerKey::CONTEXT_PREFIX->value] ?? null;
    }

    public static function getContextDocumentRoot(): ?string
    {
        return $_SERVER[ServerKey::CONTEXT_DOCUMENT_ROOT->value] ?? null;
    }

    public static function getServerAdmin(): ?string
    {
        return $_SERVER[ServerKey::SERVER_ADMIN->value] ?? null;
    }

    public static function getScriptFilename(): ?string
    {
        return $_SERVER[ServerKey::SCRIPT_FILENAME->value] ?? null;
    }

    public static function getRemotePort(): ?string
    {
        return $_SERVER[ServerKey::REMOTE_PORT->value] ?? null;
    }

    public static function getRedirectUrl(): ?string
    {
        return $_SERVER[ServerKey::REDIRECT_URL->value] ?? null;
    }

    public static function getGatewayInterface(): ?string
    {
        return $_SERVER[ServerKey::GATEWAY_INTERFACE->value] ?? null;
    }

    public static function getServerProtocol(): ?string
    {
        return $_SERVER[ServerKey::SERVER_PROTOCOL->value] ?? null;
    }

    public static function getRequestMethod(): ?string
    {
        return $_SERVER[ServerKey::REQUEST_METHOD->value] ?? null;
    }

    public static function getQueryString(): ?string
    {
        return $_SERVER[ServerKey::QUERY_STRING->value] ?? null;
    }

    public static function getRequestUri(): ?string
    {
        return $_SERVER[ServerKey::REQUEST_URI->value] ?? null;
    }

    public static function getScriptName(): ?string
    {
        return $_SERVER[ServerKey::SCRIPT_NAME->value] ?? null;
    }

    public static function getPhpSelf(): ?string
    {
        return $_SERVER[ServerKey::PHP_SELF->value] ?? null;
    }

    public static function getRequestTimeFloat(): ?float
    {
        return $_SERVER[ServerKey::REQUEST_TIME_FLOAT->value] ?? null;
    }

    public static function getRequestTime(): ?int
    {
        return $_SERVER[ServerKey::REQUEST_TIME->value] ?? null;
    }
}

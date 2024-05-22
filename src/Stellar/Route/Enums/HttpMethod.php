<?php

namespace Stellar\Route\Enums;

enum HttpMethod
{
    case CONNECT;
    case DELETE;
    case HEAD;
    case GET;
    case OPTIONS;
    case PATCH;
    case POST;
    case PUT;
    case TRACE;
}
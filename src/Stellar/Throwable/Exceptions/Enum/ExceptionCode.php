<?php

namespace Stellar\Throwable\Exceptions\Enum;

enum ExceptionCode: int
{
    case DEVELOPER_EXCEPTION = 0;
    case CATCH_EXCEPTION = 1;
    case NON_CATCH_EXCEPTION = 2;
}

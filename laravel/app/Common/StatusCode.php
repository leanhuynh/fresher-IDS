<?php

namespace App\Common;

class StatusCode {
    // HTTP status code
    public const HTTP_STATUS_OK = 200;
    public const HTTP_STATUS_CREATED = 201;
    public const HTTP_STATUS_ACCEPTED = 202;
    public const HTTP_STATUS_BAD_REQUEST = 400;
    public const HTTP_STATUS_UNAUTHORIZED = 401;
    public const HTTP_STATUS_FORBIDDEN = 403;
    public const HTTP_STATUS_NOT_FOUND = 404;
    public const HTTP_STATUS_NOT_ACCEPTABLE = 406;
    public const HTTP_STATUS_CONFLICT = 409;
    public const HTTP_STATUS_UNPROCESSABLE_ENTITY = 422;
    public const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;
}
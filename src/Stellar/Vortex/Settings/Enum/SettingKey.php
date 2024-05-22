<?php

namespace Stellar\Vortex\Settings\Enum;

enum SettingKey: string
{
    case APP = 'app';
    case APP_DEFAULT_LANGUAGE = 'app.default_language';
    case APP_DEFAULT_DATE_FORMAT = 'app.default_date_format';
    case APP_DEFAULT_DATE_TIME_FORMAT = 'app.default_date_time_format';
    case APP_PRELOAD_SETTINGS = 'app.preload_settings';
    case APP_DEFAULT_REQUEST = 'app.default_request';
    case APP_PROVIDERS = 'app.providers';
    case COSMO_STYLES = 'cosmo.styles';
    case INTERNALS = 'internals';
    case INTERNALS_LANGUAGES = 'internals.languages';
    case INTERNALS_LANGUAGES_FAKER = 'inflector.languages.faker';
    case INTERNALS_LANGUAGES_INFLECTOR = 'internals.languages.inflector';
    case ROUTE_QUERY_STRICT_MODE = 'route.query.strict_mode';
    case ROUTE_PATHS_OVERWRITE = 'route.paths.overwrite';
    case ROUTE_CUSTOM_ROUTE_FILES = 'route.custom_route_files';
    case LOGS_HANDLER = 'logs.handler';
    case LOGS_USE_FORMAT = 'logs.use_format';
    case LOGS_FORMATS = 'logs.formats';
    case LOGS_FORMATS_SINGLE = 'logs.formats.single';
    case LOGS_FORMATS_SINGLE_FILENAME = 'logs.formats.single.filename';
    case LOGS_FORMATS_TIMESTAMP = 'logs.formats.timestamp';
}
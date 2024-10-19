<?php

namespace Stellar\Settings\Enum;

enum SettingKey: string
{
    case App = 'app';
    case AppDefaultLanguage = 'app.default_language';
    case AppDefaultDateFormat = 'app.default_date_format';
    case AppDefaultDateTimeFormat = 'app.default_date_time_format';
    case AppProviders = 'app.providers';
    case AppGateways = 'app.gateways';
    case AppDefaultRequestClass = 'app.default_request_class';
    case Internal = 'internal';
    case Error = 'error';
    case InternalLanguages = 'internal.languages';
    case InternalLanguagesFaker = 'internal.inflector.languages.faker';
    case InternalLanguagesInflector = 'internal.languages.inflector';
    case InternalsHashPasswordAlgorithm = 'internal.hash.password.algorithm';
    case InternalsHashPasswordBCryptOption = 'internal.hash.password.bcrypt';
    case InternalsHashPasswordArgon2IOption = 'internal.hash.password.argon2i';
    case InternalsHashPasswordArgon2IDOption = 'internal.hash.password.argon2id';
    case RouteQueryStrictMode = 'route.query.strict_mode';
    case RoutePathsOverwrite = 'route.paths.overwrite';
    case RouteFiles = 'route.custom_route_files';
    case Log = 'log';
    case LogHandler = 'log.handler';
    case LogUseFormat = 'log.use_format';
    case LogFormats = 'log.formats';
    case LogFormatsSingle = 'log.formats.single';
    case LogFormatsSingleFilename = 'log.formats.single.filename';
    case LogFormatsTimestamp = 'log.formats.timestamp';
    case StorageDefaultDrive = 'storage.default';
    case StorageDrives = 'storage.drives';
}
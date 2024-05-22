<?php

namespace Stellar\Facades\Log\Enum;

use Monolog\Handler\AmqpHandler;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\CouchDBHandler;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\DoctrineCouchDBHandler;
use Monolog\Handler\DynamoDbHandler;
use Monolog\Handler\ElasticaHandler;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\FallbackGroupHandler;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\FleepHookHandler;
use Monolog\Handler\GelfHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\HandlerWrapper;
use Monolog\Handler\IFTTTHandler;
use Monolog\Handler\InsightOpsHandler;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\LogglyHandler;
use Monolog\Handler\LogmaticHandler;
use Monolog\Handler\MandrillHandler;
use Monolog\Handler\MongoDBHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\NewRelicHandler;
use Monolog\Handler\NoopHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\OverflowHandler;
use Monolog\Handler\ProcessHandler;
use Monolog\Handler\PsrHandler;
use Monolog\Handler\PushoverHandler;
use Monolog\Handler\RedisHandler;
use Monolog\Handler\RedisPubSubHandler;
use Monolog\Handler\RollbarHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SamplingHandler;
use Monolog\Handler\SendGridHandler;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Handler\SocketHandler;
use Monolog\Handler\SqsHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SymfonyMailerHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Handler\TestHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Monolog\Handler\ZendMonitorHandler;

/**
 * - Based on the Monolog package.
 * - "Monolog sends your logs to files, sockets, inboxes, databases and various web services. See the complete list of
 * handlers below. Special handlers allow you to build advanced logging strategies."
 * https://github.com/Seldaek/monolog.git
 */
enum LogHandler: string
{
    /**
     * - Logs records into any PHP stream, use this for log files.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/StreamHandler.php
     */
    case STREAM_HANDLER = StreamHandler::class;
    /**
     * - Logs records to a file and creates one log file per day. It will also delete files older than $maxFiles. You
     * should use logrotate for high profile setups though, this is just meant as a quick and dirty solution.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/RotatingFileHandler.php
     */
    case ROTATING_FILE_HANDLER = RotatingFileHandler::class;
    /**
     * - Logs records to the syslog.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/SyslogHandler.php
     */
    case SYSLOG_HANDLER = SyslogHandler::class;
    /**
     * - Logs records to PHP's error_log() function.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/ErrorLogHandler.php
     */
    case ERROR_LOG_HANDLER = ErrorLogHandler::class;
    /**
     * - Logs records to the STDIN of any process, specified by a command.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/ProcessHandler.php
     */
    case PROCESS_HANDLER = ProcessHandler::class;
    /**
     * - Sends emails using PHP's mail() function.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/NativeMailerHandler.php
     */
    case NATIVE_MAILER_HANDLER = NativeMailerHandler::class;
    /**
     * - Sends emails using a symfony/mailer instance.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/SymfonyMailerHandler.php
     */
    case SYMFONY_MAILER_HANDLER = SymfonyMailerHandler::class;
    /**
     * - Sends mobile notifications via the Pushover API.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/PushoverHandler.php
     */
    case PUSHOVER_HANDLER = PushoverHandler::class;
    /**
     * - Logs records to a Slack account using Slack Webhooks.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/SlackWebhookHandler.php
     */
    case SLACK_WEBHOOK_HANDLER = SlackWebhookHandler::class;
    /**
     * - Logs records to a Slack account using the Slack API (complex setup).
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/SlackHandler.php
     */
    case SLACK_HANDLER = SlackHandler::class;
    /**
     * - Sends emails via the SendGrid API.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/SendGridHandler.php
     */
    case SEND_GRID_HANDLER = SendGridHandler::class;
    /**
     * - Sends emails via the Mandrill API using a Swift_Message instance.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/MandrillHandler.php
     */
    case MANDRILL_HANDLER = MandrillHandler::class;
    /**
     * - Logs records to a Fleep conversation using Webhooks.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/FleepHookHandler.php
     */
    case FLEEP_HOOK_HANDLER = FleepHookHandler::class;
    /**
     * - Notifies an IFTTT trigger with the log channel, level name and message.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/IFTTTHandler.php
     */
    case IFTTT_HANDLER = IFTTTHandler::class;
    /**
     * - Logs records to a Telegram bot account.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/TelegramBotHandler.php
     */
    case TELEGRAM_BOT_HANDLER = TelegramBotHandler::class;
    /**
     * - Logs records to a HipChat chat room using its API. Deprecated and removed in Monolog 2.0, use Slack handlers
     * instead, see Atlassian's announcement
     * - https://github.com/Seldaek/monolog/blob/1.x/src/Monolog/Handler/HipChatHandler.php
     */
//    case HIP_CHAT_HANDLER = HipChatHandler;
    /**
     * - Sends emails using a Swift_Mailer instance. Deprecated and removed in Monolog 3.0. Use SymfonyMailerHandler
     * instead.
     * - https://github.com/Seldaek/monolog/blob/2.x/src/Monolog/Handler/SwiftMailerHandler.php
     */
//    case SWIFT_MAILER_HANDLER = SwiftMailerHandler;
    /**
     * - Logs records to sockets, use this for UNIX and TCP sockets. See an example.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/SocketHandler.php
     */
    case SOCKET_HANDLER = SocketHandler::class;
    /**
     *  - Logs records to an AMQP compatible server. Requires the php-amqp extension (1.0+) or php-amqplib library.
     *  - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/AmqpHandler.php
     */
    case AMQP_HANDLER = AmqpHandler::class;
    /**
     * - Logs records to a Graylog2 server. Requires package graylog2/gelf-php.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/GelfHandler.php
     */
    case GELF_HANDLER = GelfHandler::class;
    /**
     * - Logs records to the Zend Monitor present in Zend Server.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/ZendMonitorHandler.php
     */
    case ZEND_MONITOR_HANDLER = ZendMonitorHandler::class;
    /**
     * - Logs records to a NewRelic application.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/NewRelicHandler.php
     */
    case NEW_RELIC_HANDLER = NewRelicHandler::class;
    /**
     * - Logs records to a Loggly account.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/LogglyHandler.php
     */
    case LOGGLY_HANDLER = LogglyHandler::class;
    /**
     * - Logs records to a Rollbar account.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/RollbarHandler.php
     */
    case ROLLBAR_HANDLER = RollbarHandler::class;
    /**
     * - Logs records to a remote Syslogd server.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/SyslogUdpHandler.php
     */
    case SYSLOG_UDP_HANDLER = SyslogUdpHandler::class;
    /**
     * - Logs records to a LogEntries account.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/LogEntriesHandler.php
     */
    case LOGS_ENTRIES_HANDLER = LogEntriesHandler::class;
    /**
     * - Logs records to an InsightOps account.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/InsightOpsHandler.php
     */
    case INSIGHT_OPS_HANDLER = InsightOpsHandler::class;
    /**
     * - Logs records to a Logmatic account.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/LogmaticHandler.php
     */
    case LOGMATIC_HANDLER = LogmaticHandler::class;
    /**
     * - Logs records to an AWS SQS queue.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/SqsHandler.php
     */
    case SQS_HANDLER = SqsHandler::class;
    /**
     * - Logs records to a Sentry server using raven. Deprecated and removed in Monolog 2.0, use sentry/sentry 2.x and
     * the Sentry\Monolog\Handler class instead.
     * - https://github.com/Seldaek/monolog/blob/1.x/src/Monolog/Handler/RavenHandler.php
     */
    //    case RAVEN_HANDLER = RavenHandler;
    /**
     * - Handler for FirePHP, providing inline console messages within FireBug.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/FirePHPHandler.php
     */
    case FIRE_PHP_HANDLER = FirePHPHandler::class;
    /**
     * - Handler for ChromePHP, providing inline console messages within Chrome.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/ChromePHPHandler.php
     */
    case CHROME_PHP_HANDLER = ChromePHPHandler::class;
    /**
     * - Handler to send logs to browser's Javascript console with no browser extension required. Most browsers
     * supporting console API are supported.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/BrowserConsoleHandler.php
     */
    case BROWSER_CONSOLE_HANDLER = BrowserConsoleHandler::class;
    /**
     * - Logs records to a redis server's key via RPUSH.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/RedisHandler.php
     */
    case REDIS_HANDLER = RedisHandler::class;
    /**
     * - Logs records to a redis server's channel via PUBLISH.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/RedisPubSubHandler.php
     */
    case REDIS_PUB_SUB_HANDLER = RedisPubSubHandler::class;
    /**
     * - Handler to write records in MongoDB via a Mongo extension connection.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/MongoDBHandler.php
     */
    case MONGO_DB_HANDLER = MongoDBHandler::class;
    /**
     * - Logs records to a CouchDB server.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/CouchDBHandler.php
     */
    case COUCH_DB_HANDLER = CouchDBHandler::class;
    /**
     * - Logs records to a CouchDB server via the Doctrine CouchDB ODM.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/DoctrineCouchDBHandler.php
     */
    case DOCTRINE_COUCH_DB_HANDLER = DoctrineCouchDBHandler::class;
    /**
     * - Logs records to an Elasticsearch server using ruflin/elastica.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/ElasticaHandler.php
     */
    case ELASTICA_HANDLER = ElasticaHandler::class;
    /**
     * - Logs records to an Elasticsearch server.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/ElasticsearchHandler.php
     */
    case ELASTIC_SEARCH_HANDLER = ElasticsearchHandler::class;
    /**
     * - Logs records to a DynamoDB table with the AWS SDK.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/DynamoDbHandler.php
     */
    case DYNAMO_DB_HANDLER = DynamoDbHandler::class;
    /**
     * - A very interesting wrapper. It takes a handler as a parameter and will accumulate log records of all levels
     * until a record exceeds the defined severity level. At which point it delivers all records, including those of
     * lower severity, to the handler it wraps. This means that until an error actually happens you will not see
     * anything in your logs, but when it happens you will have the full information, including debug and info records.
     * This provides you with all the information you need, but only when you need it.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/FingersCrossedHandler.php
     */
    case FINGERS_CROSSED_HANDLER = FingersCrossedHandler::class;
    /**
     * - Useful if you are sending notifications or emails when critical errors occur. It takes a handler as a parameter
     * and will accumulate log records of all levels until the end of the request (or flush() is called). At that point
     * it delivers all records to the handler it wraps, but only if the records are unique over a given time period
     * (60seconds by default). If the records are duplicates they are simply discarded. The main use of this is in case
     * of critical failure like if your database is unreachable for example all your requests will fail and that can
     * result in a lot of notifications being sent. Adding this handler reduces the amount of notifications to a
     * manageable level.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/DeduplicationHandler.php
     */
    case DEDUPLICATION_HANDLER = DeduplicationHandler::class;
    /**
     * - This handler extends the GroupHandler ignoring exceptions raised by each child handler. This allows you to
     * ignore issues where a remote tcp connection may have died but you do not want your entire application to crash
     * and may wish to continue to log to other handlers.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/WhatFailureGroupHandler.php
     */
    case WHAT_FAILURE_GROUP_HANDLER = WhatFailureGroupHandler::class;
    /**
     * - This handler extends the GroupHandler ignoring exceptions raised by each child handler, until one has handled
     * without throwing. This allows you to ignore issues where a remote tcp connection may have died but you do not
     * want your entire application to crash and may wish to continue to attempt logging to other handlers, until one
     * does not throw an exception.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/FallbackGroupHandler.php
     */
    case FALLBACK_GROUP_HANDLER = FallbackGroupHandler::class;
    /**
     * - This handler will buffer all the log records it receives until close() is called at which point it will call
     * handleBatch() on the handler it wraps with all the log messages at once. This is very useful to send an email
     * with all records at once for example instead of having one mail for every log record.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/BufferHandler.php
     */
    case BUFFER_HANDLER = BufferHandler::class;
    /**
     * - This handler groups other handlers. Every record received is sent to all the handlers it is configured with.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/GroupHandler.php
     */
    case GROUP_HANDLER = GroupHandler::class;
    /**
     * - This handler only lets records of the given levels through to the wrapped handler.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/FilterHandler.php
     */
    case FILTER_HANDLER = FilterHandler::class;
    /**
     * - Wraps around another handler and lets you sample records if you only want to store some of them.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/SamplingHandler.php
     */
    case SAMPLING_HANDLER = SamplingHandler::class;
    /**
     * - This handler handles anything by doing nothing. It does not stop processing the rest of the stack. This can be
     * used for testing, or to disable a handler when overriding a configuration.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/NoopHandler.php
     */
    case NOOP_HANDLER = NoopHandler::class;
    /**
     * - Any record it can handle will be thrown away. This can be used to put on top of an existing handler stack to
     * disable it temporarily.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/NullHandler.php
     */
    case NULL_HANDLER = NullHandler::class;
    /**
     * - Can be used to forward log records to an existing PSR-3 logger
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/PsrHandler.php
     */
    case PSR_HANDLER = PsrHandler::class;
    /**
     * - Used for testing, it records everything that is sent to it and has accessors to read out the information.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/TestHandler.php
     */
    case TEST_HANDLER = TestHandler::class;
    /**
     * - A simple handler wrapper you can inherit from to create your own wrappers easily.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/HandlerWrapper.php
     */
    case HANDLER_WRAPPER = HandlerWrapper::class;
    /**
     * - This handler will buffer all the log messages it receives, up until a configured threshold of number of
     * messages of a certain level is reached, after it will pass all log messages to the wrapped handler. Useful for
     * applying in batch processing when you're only interested in significant failures instead of minor, single
     * erroneous events.
     * - https://github.com/Seldaek/monolog/blob/main/src/Monolog/Handler/OverflowHandler.php
     */
    case OVERFLOW_HANDLER = OverflowHandler::class;
}
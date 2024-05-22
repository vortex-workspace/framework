<?php

namespace Stellar;

use Stellar\Helpers\StrTool;
use Stellar\Helpers\Typography\Enum\Typography;
use Stellar\Navigation\Enums\ApplicationPath;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Settings\Exceptions\InvalidSettingException;

class Setting
{
    private static array $settings = [];

    /**
     * @param string $setting
     * @param mixed|null $default
     * @return mixed
     * @throws InvalidSettingException
     */
    public static function get(string $setting, mixed $default = null): mixed
    {
        $separated_setting = self::getSettingExplodedTrace($setting);

        if (($result = self::tryGetFromLoadedSettings($separated_setting)) !== null) {
            return $result;
        }

        self::tryLoadSettingFileFromApplication($separated_setting[0]);

        return self::tryGetFromLoadedSettings($separated_setting) ?? $default;
    }

    /**
     * @param string $setting_file
     * @return void
     */
    private static function tryLoadSettingFileFromApplication(string $setting_file): void
    {
        try {
            $settings = require(root_path(ApplicationPath::Settings->additionalPath("$setting_file.php")));

            self::updateSettings($setting_file, $settings);
        } catch (PathNotFound) {
        }
    }

    /**
     * @param array $setting_key
     * @return mixed
     */
    private static function tryGetFromLoadedSettings(array $setting_key): mixed
    {
        if (isset(self::$settings[$setting_key[0]])) {
            if (count($setting_key) === 1) {
                return self::$settings[$setting_key[0]];
            }

            return self::getKeyFromSetting($setting_key);
        }

        return null;
    }

    private static function getKeyFromSetting(array $settings): mixed
    {
        $search_array = self::$settings[$settings[0]];
        unset($settings[0]);

        foreach ($settings as $setting_key) {
            if (!isset($search_array[$setting_key])) {
                return null;
            }

            $search_array = $search_array[$setting_key];
        }

        return $search_array;
    }

    /**
     * @param string $setting
     * @return array
     * @throws InvalidSettingException
     */
    private static function getSettingExplodedTrace(string $setting): array
    {
        if (StrTool::contains($setting, '.php')) {
            $setting = StrTool::substring($setting, 0, -4);
        }

        /** @var array|false $exploded_trace */
        $exploded_trace = explode(Typography::Dot->value, $setting);

        if ($exploded_trace === false || $exploded_trace[0] === Typography::EmptyString->value) {
            throw new InvalidSettingException($setting);
        }

        return $exploded_trace;
    }

    private static function updateSettings(string $key, array $value): void
    {
        self::$settings[$key] = array_merge(
            self::$settings[$key] ?? [],
            $value
        );
    }

    public static function uploadFileSetting(string $full_path, ?string $setting_key = null): void
    {
        if ($setting_key === null) {
            $setting_key = StrTool::of($full_path)->afterLast(OS_SEPARATOR)->substring(0, -4)->get();
        }

        self::updateSettings($setting_key, require_once($full_path));
    }
}
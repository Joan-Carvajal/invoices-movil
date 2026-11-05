<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;

class NativeApp
{
    public static function running(): bool
    {
        return (bool) config('nativephp-internal.running');
    }

    /**
     * La WebView de Android no abre el selector de archivos de un input file.
     */
    public static function android(): bool
    {
        return self::running() && config('nativephp-internal.platform') === 'android';
    }

    /**
     * En el dispositivo no hay seeder: el primer arranque crea la cuenta local.
     */
    public static function needsSetup(): bool
    {
        return self::running() && User::query()->doesntExist();
    }
}

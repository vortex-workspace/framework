<?php

namespace Stellar;

use Stellar\Storage\StorageDrive;

class Storage
{
    public static function drive(string $drive, ?string $partition = null): StorageDrive
    {
        return new StorageDrive($drive, $partition);
    }

    public static function get()
    {

    }

    public static function url()
    {

    }

    public static function put()
    {

    }

    public static function turnPublic()
    {

    }

    public static function turnPrivate()
    {

    }
}
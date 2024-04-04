<?php

namespace Davide7h\EncodedIds\Traits; 

use Davide7h\EncodedIds\Services\EncodingService;

trait HasEncodedId
{
    protected static $es;


    public static function getEncodingService()
    {
        if(!isset(self::$encodingService))
        {
            self::$es = new EncodingService();
        }
    }

    public function getEncodedIdAttribute(): string
    {
        self::getEncodingService();

        return self::$es->encode($this->id);
    }

    public function setEncodedId(String $encoded_id): ?int
    {
        self::getEncodingService();

        $this->id = self::$es->decode($encoded_id);

        return $this->id;
    }

    public static function find(String | Int $id)
    {
        self::getEncodingService();
        $id = self::$es->decode($id) ?? $id;

        return self::query()->find($id);
    }

    public static function findOrFail(String | Int $id)
    {
            self::getEncodingService();
            $id = self::$es->decode($id) ?? $id;

        return self::query()->findOrFail($id);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if ($field == 'encoded_id') {
            self::getEncodingService();
            $field = 'id';
            $value = self::$es->decode($value);
        }

        return $this->resolveRouteBindingQuery($this, $value, $field)->first();
    }
}

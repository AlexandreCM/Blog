<?php
namespace App;

class ObjectHelper {

    public static function hydrate($object, array $data):void
    {
        $fields = array_keys($data);
        foreach ($fields as $field) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
            $object->$method($data[$field]);
        }
    }
}
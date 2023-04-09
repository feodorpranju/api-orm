<?php


namespace Feodorpranju\ApiOrm\Enumerations;


enum FieldType: string
{
    case String = "string";
    case Int = "integer";
    case Bool = "boolean";
    case Float = "float";
    case Enum = "enumeration";
    case File = "file";
    case Url = "url";
    case Link = "link";
    case Cast = "cast"; //custom type
    case Phone = "phone";
    case Email = "email";
    case Date = "date";
    case Datetime = "datetime";
    case Time = "time";
}
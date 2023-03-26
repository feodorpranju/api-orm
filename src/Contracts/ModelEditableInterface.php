<?php


namespace Feodorpranju\ApiOrm\Contracts;


interface ModelEditableInterface
{

    /**
     * Saves model changes or creates new record if not exist
     *
     * @return ModelInterface
     */
    public static function save(): ModelInterface;

    /**
     * Return instance of select
     *
     * @param array|null $fieldIDs selecting field ID's. null for all
     * @return SelectQueryInterface
     */
    public static function select(array $fieldIDs = null): SelectQueryInterface;

    /**
     * Sets Model's multiple field values
     *
     * @param array $fields [name => value]
     * @return $this
     */
    public function setFields(array $fields): self;

    /**
     * Sets model's field value
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setField(string $name, mixed $value): self;
}
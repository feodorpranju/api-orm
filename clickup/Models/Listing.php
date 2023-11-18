<?php


namespace Feodorpranju\ClickUp\SDK\Models;


use Feodorpranju\ClickUp\SDK\Models\Fields\FieldSettings;
use Feodorpranju\ClickUp\SDK\Traits\HasParent;
use Illuminate\Support\Collection;
use Feodorpranju\ApiOrm\Enumerations\FieldType;

class List extends AbstractModel
{
    use HasParent;

    public const ENTITY_NAME = 'folder';

    /**
     * For HasParent trait
     */
    public const PARENT = Space::class;
    public const GET_BY_PARENT_PATTERN = 'space/?/folder';

    /**
     * @inheritDoc
     */
    public function get(int|string $id): ?static
    {
        // TODO: Implement get() method.
    }

    public function fields(): Collection
    {
        return collect([
            'id' => new FieldSettings('id', FieldType::Int),
            'space_id' => new FieldSettings('space_id', FieldType::Int),
            'task_count' => new FieldSettings('task_count', FieldType::Int),
            'orderindex' => new FieldSettings('orderindex', FieldType::Int),
            'name' => new FieldSettings('name', FieldType::String),
            'space' => new FieldSettings('space', FieldType::Cast),
            'statuses' => new FieldSettings('statuses', FieldType::Cast, true),
            'lists' => new FieldSettings('lists', FieldType::Cast, true),
            'private' => new FieldSettings('private', FieldType::Bool),
            'permission_level' => new FieldSettings('permission_level', FieldType::String),
            'archived' => new FieldSettings('archived', FieldType::Bool),
            'hidden' => new FieldSettings('hidden', FieldType::Bool),
            'override_statuses' => new FieldSettings('override_statuses', FieldType::Bool),
        ]);
    }
}
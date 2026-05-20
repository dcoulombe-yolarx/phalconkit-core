# Models And Eager Loading

PhalconKit models build on `Phalcon\Mvc\Model` and add generated model layers,
relationship-aware assignment, model behaviors, and batch eager loading.

Official Phalcon references:

- Models: https://docs.phalcon.io/5.13/db-models/
- Relationships: https://docs.phalcon.io/5.13/db-models-relationships/
- Behaviors: https://docs.phalcon.io/5.13/db-models-behaviors/
- Model validation: https://docs.phalcon.io/5.13/db-models-validation/

## Generated And Concrete Layers

Generated abstract models carry schema knowledge:

- properties and comments
- getters and setters
- column maps
- default relationships
- default validations
- generated interfaces
- enum classes where supported by the database

Concrete models carry application behavior:

```php
<?php

namespace App\Models;

final class Project extends Abstracts\ProjectAbstract
{
    public function isOpen(): bool
    {
        return !$this->isDeleted() && $this->getStatus() === 'open';
    }
}
```

When the schema changes, regenerate the abstract layer and review concrete
models for new domain rules.

## Relationship Payloads

Generated relationship aliases are used by REST save payloads and eager loading.
Typical alias shapes are:

- `UserEntity` for a single related model.
- `UserList` for a one-to-many or many-to-many list.
- `UserNode` for join/node-table records.

Controllers can allow relation writes through nested `initializeSaveFields()`
configuration:

```php
$this->setSaveFields(new Collection([
    'label',
    'usernode' => [
        'userId',
        'type',
        'deleted',
    ],
]));
```

Keep relation payloads explicit. Do not expose every nested field just because a
relationship exists.

## Eager Loading

Use eager loading when a response or workflow needs related data. This avoids
lazy-loading loops and keeps relation graphs visible at the query boundary.

Model-level examples:

```php
$projects = Project::findWith([
    'UserNode.UserEntity',
    'CategoryList',
], [
    'conditions' => 'deleted <> 1',
]);

$project = Project::findFirstWith([
    'UserNode.UserEntity',
], [
    'conditions' => 'id = :id:',
    'bind' => ['id' => $id],
]);
```

Controller-level examples:

```php
public function initializeWith(): void
{
    $this->setWith(new Collection([
        'UserNode.UserEntity',
        'CategoryList',
    ]));
}
```

Use relation-level query builders when a relation needs extra constraints,
ordering, or limits. Keep expensive relation graphs out of list requests unless
the UI really needs them.

## Model Behaviors

PhalconKit model traits and behaviors cover common persistence rules:

- UUIDs and UUIDv7 identifiers.
- Soft delete and restore fields.
- Created, updated, deleted, and restored blameable fields.
- Slug generation.
- Position/order helpers.
- Snapshot and cache support.
- Security checks against identity roles.
- Replication helpers.

Use generated defaults for schema-derived behavior and concrete models for
business-specific behavior.

## Practical Rules

- Treat the database schema as the source of truth for generated model shape.
- Keep custom relationships in concrete models when the scaffolder cannot infer
  them safely.
- Prefer `findWith()` and `findFirstWith()` for known relation graphs.
- Use transformers for heavy nested API resources.
- Keep model methods focused on domain behavior, not controller formatting.

# Architecture

Phalcon Kit is a convention layer over Phalcon. It keeps native Phalcon
concepts visible while adding reusable structure for bootstrap, configuration,
providers, models, REST controllers, identity, CLI, WebSocket workers, and
maintainer tooling.

Official Phalcon references:

- MVC: https://docs.phalcon.io/5.13/mvc/
- Dependency injection: https://docs.phalcon.io/5.13/di/
- Loader/autoloading: https://docs.phalcon.io/5.13/autoload/
- Routing: https://docs.phalcon.io/5.13/routing/
- Models: https://docs.phalcon.io/5.13/db-models/

## Runtime Flow

A normal HTTP request follows this shape:

1. `public/index.php` requires the project entrypoint.
2. The project entrypoint loads `loader.php`.
3. `App\Bootstrap` configures PhalconKit with app-owned config.
4. Service providers register the DI services.
5. The selected module handles routing, dispatch, controller execution, and the
   response.

CLI and WebSocket entrypoints use the same bootstrap with a different mode:

```php
new Bootstrap('cli');
new Bootstrap('ws');
```

That keeps service configuration, identity rules, model aliases, and providers
consistent across HTTP, CLI, and WebSocket contexts.

## Ownership Boundaries

Keep generated framework structure and application logic separate:

- `Config/`: app-owned configuration, provider overrides, permissions, modules,
  aliases, and integrations.
- `Models/Abstracts/`: generated schema layer. Regenerate it when the database
  changes.
- `Models/`: app-owned concrete models and business logic.
- `Modules/Api/Controllers/`: resource-specific REST field policies and
  workflow endpoints.
- `Modules/Cli/Tasks/`: operational tasks, imports, exports, maintenance jobs,
  and scaffolding entrypoints.
- `Modules/Ws/Tasks/`: WebSocket task handlers.
- `resources/migrations/`: database migration history.

Do not put domain rules in generated abstracts. Put them in concrete models,
controllers, services, validators, or app config.

## Extension Points

Common extension points are:

- Config classes extending `PhalconKit\Bootstrap\Config`.
- Service providers extending `PhalconKit\Provider\AbstractServiceProvider`.
- Concrete models extending generated abstract models.
- API controllers extending the app API base controller.
- Permission config classes merged into the app config.
- Fractal transformers for complex API output.
- Model behaviors for reusable lifecycle logic.

Prefer app-owned extensions over editing vendor code or generated files.

## Native Phalcon First

When unsure, start from the native Phalcon concept:

- DI services are still Phalcon DI services.
- Models are still Phalcon ORM models.
- Controllers still run through the Phalcon dispatcher.
- Validation still uses Phalcon validation primitives.
- Routing still uses Phalcon routing and dispatcher semantics.

PhalconKit adds conventions, generators, helpers, and defaults around those
components. It does not replace the underlying framework.

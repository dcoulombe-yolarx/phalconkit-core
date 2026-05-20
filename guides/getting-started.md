# Getting Started

Use the `phalcon-kit/app` skeleton for new applications whenever possible. It
contains the recommended directory structure, entrypoints, environment files,
default modules, and configuration examples.

```shell
composer create-project phalcon-kit/app my-app
```

For an existing Phalcon project, install the core package directly:

```shell
composer require phalcon-kit/core
```

New projects should use `phalcon-kit/core`. Older projects may still reference
`zemit-cms/core`; keep those constraints pinned until you are ready to test the
package-name migration.

## Project Layout

The app skeleton usually contains:

```text
app/
  Bootstrap.php
  Config/
  Models/
  Modules/
resources/
  migrations/
public/
  index.php
loader.php
index.php
cli
websocket
```

The public web root should be `public/`. Do not point a web server at the
project root.

## Entrypoints

A typical project has small entrypoints that load the optimized Phalcon loader
and then run the application bootstrap.

```php
<?php

use Phalcon\Autoload\Loader;

const APP_NAMESPACE = 'App';
const ROOT_PATH = __DIR__ . '/';
const VENDOR_PATH = ROOT_PATH . 'vendor/';
const APP_PATH = ROOT_PATH . 'app/';

$loader = new Loader();
$loader->setFiles([VENDOR_PATH . 'autoload.php']);
$loader->setNamespaces([APP_NAMESPACE => APP_PATH]);
$loader->setFileCheckingCallback(null);
$loader->register();

return $loader;
```

```php
<?php

use App\Bootstrap;

require 'loader.php';

echo (new Bootstrap())->run();
```

CLI and WebSocket entrypoints use the same bootstrap with an explicit mode:

```php
#!/usr/bin/env php
<?php

use App\Bootstrap;

require 'loader.php';

echo (new Bootstrap('cli'))->run();
```

```php
#!/usr/bin/env php
<?php

use App\Bootstrap;

require 'loader.php';

echo (new Bootstrap('ws'))->run();
```

For public web requests, keep `public/index.php` tiny:

```php
<?php

require '../index.php';
```

Keep entrypoints boring. Put application behavior in modules, controllers,
tasks, services, and models.

## Bootstrap Class

Applications normally subclass `PhalconKit\Bootstrap` and provide application
configuration.

```php
<?php

namespace App;

use App\Config\Config;

final class Bootstrap extends \PhalconKit\Bootstrap
{
    public function initialize(): void
    {
        $this->setConfig(new Config());
    }
}
```

Bootstrap mode controls which application runtime is created:

- default mode: HTTP MVC/API runtime
- `cli`: CLI console runtime
- `ws`: WebSocket runtime

## Local Development

For quick local experiments, PHP's built-in web server can serve the public
front controller:

```shell
php -S 127.0.0.1:8000 -t public public/index.php
```

Use a real web server such as Apache, Nginx, Caddy, or a containerized setup for
shared development and production-like environments.

## First Checks

After installing dependencies and configuring `.env`, run:

```shell
composer validate --strict --no-check-publish
composer phpunit
```

If the application uses database-backed models, run migrations before testing
API resources:

```shell
./bin/migration-list.sh
./bin/migration-run.sh
```

## Next Steps

- Understand the package layout in [Architecture](architecture.md).
- Configure modules, providers, model aliases, permissions, and integrations in
  [Configuration](configuration.md).
- Generate model abstracts and relationships from the database with
  [Database And Scaffolding](database-scaffolding.md).
- Learn model behavior and eager loading in
  [Models And Eager Loading](models-and-eager-loading.md).
- Build model-backed APIs with [REST APIs](rest-api.md).
- Configure roles and row-level access with
  [Identity And Permissions](identity-and-permissions.md).
- Configure PHP-FPM and WebSocket proxying with
  [Web Server And WebSocket](web-server-and-websocket.md).

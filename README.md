## Pimcore Task

## Getting started with Docker

Use Docker to set up this project. You don't need to have a PHP environment with composer installed.

## Prerequisites

* Git 
* Your user must be allowed to run docker commands.
* You must have docker compose installed.
* Your user must be allowed to change file permissions.

### Follow these steps
1. Clone this project : 
    * `git clone https://github.com/ramyakant-dev/pimcore-task.git`

2. Go to your project folder 
    * `cd project-folder/`

3. Part of the this project is a docker compose file
    * Run `sed -i "s|#user: '1000:1000'|user: '$(id -u):$(id -g)'|g" docker-compose.yaml` to set the correct user id and group id.
    * Start the needed services with `docker compose up -d`

4. Run composer install 
    * `sudo docker compose exec php composer install`

5. Install pimcore and initialize the DB
    * `docker compose exec php vendor/bin/pimcore-install --mysql-host-socket=db --mysql-username=pimcore --mysql-password=pimcore --mysql-database=pimcore`
    * When asked for admin user and password: Choose freely
    * Follow the process. This project doesn't need any extra bundles to be installed.
    * If you select to install the SimpleBackendSearchBundle please make sure to add the `pimcore_search_backend_message` to your `.docker/supervisord.conf` file inside value for 'command' like `pimcore_maintenance` already is.

6. DONE - You can now visit your pimcore instance:
    * The frontend: <http://localhost>
    * The admin interface, using the credentials you have chosen above: <http://localhost/admin>

## With PHP environment & composer

1. Clone this project : 
    * `git clone https://github.com/ramyakant-dev/pimcore-task.git`

2. Go to your project folder 
    * `cd project-folder/`

3. Run composer install 
    * `composer install`

4. Install Pimcore & initialize DB
    * `./vendor/bin/pimcore-install`

5. Setup Apache & Virtual Host 
    * Point your virtual host to `my-project/public`
    * [Only for Apache] Create `my-project/public/.htaccess` according to https://pimcore.com/docs/platform/Pimcore/Installation_and_Upgrade/System_Setup_and_Hosting Apache_Configuration/ 
    * Open https://your-host/admin in your browser
    * Done! 😎

## Pimcore Platform Version
By default, Pimcore Platform Version is added as a dependency which ensures installation of compatible and in combination 
with each other tested versions of additional Pimcore modules. More information about the Platform Version can be found in the 
[Platform Version docs](https://github.com/pimcore/platform-version). 

It might be necessary to update a specific Pimcore module to a version that is not included in the Platform Version.
In that case, you need to remove the `platform-version` dependency from your `composer.json` and update the module to
the desired version.
Be aware that this might lead to a theoretically compatible but untested combination of Pimcore modules.

## Other demo/skeleton packages
- [Pimcore Basic Demo](https://github.com/pimcore/demo)

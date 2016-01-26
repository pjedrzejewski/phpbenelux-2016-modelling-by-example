PHPBenelux 2016 - Modelling By Example
======================================

In this file you can find all steps with their branch names.

Refer to the "Troubleshooting" section for README file if you are stuck at any point of the workshop.

SETUP
-----

* Clone the repository:

```bash
git clone git@github.com:pjedrzejewski/phpbenelux-2016-modelling-by-example.git workshop
cd workshop
```

* Switch to your working branch and the first step:

```bash
git checkout -b workshop origin/step-01-fresh-start
```

* Install vendors:

```bash
wget http://getcomposer.org/composer.phar
php composer.phar install
```

Of if you have Composer installed globally already, just do:

```
composer install
```

* Verify everything works by running the following commands:

```bash
bin/phpspec run -fpretty
bin/behat --suite=domain
bin/behat --suite=ui

./console
```

You should see some yellow texts and list of Symfony commands.

* Finally, start the built-in PHP server: 

```
./console server:start --docroot web
```

You are set, let's get started!

Step 1: Fresh start
-------------------

``step-01-fresh-start``

This step has a basic application setup in place, working and all!

Step 2: Setting up the features
-------------------------------

``step-02-setting-up-the-features``

* Copy ``behat.yml.dist`` to ``behat.yml``;
* Have a look at ``domain`` and ``ui`` suites;
* Run Behat's ``domain`` suite with the following command:

```bash
bin/behat --suite=domain
```

* Generate the step definitions with the following command:

```bash``
bin/behat --suite=domain --append-snippets
```

Step 3: First step definitions
------------------------------

``step-03-first-step-definitions``

* Start filling in the step definitions.

Step 4: Implementing the Book model
-----------------------------------

``step-04-implementing-the-book``

* Using PHPSpec, implement ``Book`` model with value objects ``BookTitle`` and ``Isbn``;
* To keep things simple, we assume that our library has only new books, with ISBN13;
* Use the following snippet of code for ISBN validation:

```php
$number = (string) $number;
$number = str_replace('-', '', $number);

if (!ctype_digit($number)) {
    return false;
}

$length = strlen($number);

if ($length !== 13) {
    return false;
}

$checkSum = 0;

for ($i = 0; $i < 13; $i += 2) {
    $checkSum += $number{$i};
}
for ($i = 1; $i < 12; $i += 2) {
    $checkSum += $number{$i} * 3;
}

if(0 !== $checkSum % 10) {
    return false;
}

return true;
```

Step 5: Make some steps green!
------------------------------

``step-05-make-some-steps-green``

* Use the newly created domain models for the first Behat steps.

Step 6: Initial library model
-----------------------------

``step-06-initial-library``

* Define the ``LibraryInterface``;
* Implement ``InMemoryLibrary`` using PHPSpec;
* Use it in our catalog related steps.

Step 7: Removing books from the catalog
---------------------------------------

``step-07-removing-books-from-the-catalog``

* Fill the next step definitions;
* Write new specs;
* Add new method ``remove`` to our library interface;
* Make it green, baby!

Step 8: Implementing the search
-------------------------------

``step-08-the-search``

* Again, fill our step definitions;
* Write new specs for our InMemoryLibrary;
* Implement SearchResults model;
* Make it green, wooohoo!

Step 9: ISBN uniqueness validation
----------------------------------

``step-09-isbn-validation``

* You know what to do, right?
* Fill next step definitions;
* Write specs for new behavior of the library;
* Verify it works!

Step 10: The UI context
-----------------------

``step-10-the-ui-context``

* Generate step definitions for the UI context using the following command:

bin/behat --suite=ui --append-snippets

* Fill the step definitions;
* Write a spec for DoctrineLibrary;
* Implement the DoctrineLibrary;
* Register it as a ``app.library`` service:

```yaml
services:
  app.doctrine.manager.book:
    alias: doctrine.orm.default_entity_manager
  app.doctrine.repository.book:
    class: Doctrine\ORM\EntityRepository
    factory: [@app.doctrine.manager.book, 'getRepository']
    arguments: ['App\Domain\Book']

  app.catalog:
    class: App\Bundle\BookCatalog\DoctrineBookCatalog
    arguments:
      - @app.doctrine.manager.book
      - @app.doctrine.repository.book
```

* Map our Book domain model as Doctrine entity:

```yaml
# config/doctrine/Book.orm.yml

App\Domain\Book:
  type: entity
  embedded:
    isbn:
      class: App\Domain\Isbn
    title:
      class: App\Domain\BookTitle
      columnPrefix: false
```

```yaml
# config/doctrine/Isbn.orm.yml

App\Domain\Isbn:
  type: embeddable
  id:
    number:
      type: string
      id: true
```

```yaml
# config/doctrine/BookTitle.orm.yml

App\Domain\BookTitle:
  type: embeddable
  fields:
    title:
      type: string
      column: title
```

* Update the database schema:

```bash
./console doctrine:schema:create
```

* Implement LibraryControllerSpec; 
* Add routing configuration to ``routing.yml``:

```yaml
app_library_search:
  path: /search
  methods: [GET]
  defaults:
    _controller: app.controller.library:searchByIsbnAction
```

* Register the controller as a service:

```yaml
services:
    app.controller.library:
        class: App\Bundle\Controller\LibraryController
        arguments:
            - @app.library
            - @templating
```

Run all your specs and suites:

```bash
bin/phpspec run -fpretty
bin/behat
```

Green? Hey, maybe we can finally open the browser?

```bash
open http://127.0.0.1:8000/search
```

ISBNs to test:

* 978-0618640157 
* 978-1-56619-909-4

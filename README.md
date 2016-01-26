PHPBenelux 2016 - Modelling By Example
======================================

Welcome to the workshop repository!

**GET STARTED!** Have a look at the ``STEPS.md`` file for installation instructions and tasks!

Running your phpspec and Behat suites
-------------------------------------

```bash
$ bin/phpspec run -fpretty

$ bin/behat --suite=domain
$ bin/behat --suite=ui

$ bin/behat
```

Troubleshooting
---------------

Whenever you get stuck or want to proceed to the next step, just follow the guide below:

``` bash
$ git clean -fd && git reset HEAD . && git checkout .
$ git checkout -b workshop origin/step-01-fresh-start
```

This will create a new starting point for you. Just replace ``step-01-fresh-start`` with appropriate step branch name.

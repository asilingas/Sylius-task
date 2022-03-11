<p>This task is made from two parts: development and logical point of view.>
</p>
<p>Please prepare your development environment to use Sylius, task is for products and all related tables like taxons (categories), attributes, translations, etc. But from you, we want just products part.</p>
Business requirements:
<li>Must have fast CSV import</li> 
<li>Must have fast CSV export</li>
<li>Import and Export CSV should have the exact same column schema</li>
<li>Must have support for Microsoft EXCEL formatted CSV</li>
<li>Must support regular UTF-8 CSV format</li>
<li>Must have progress bar for currently importing files (Endpoint can send updated numbers)</li>
<li>Must have progress bar for currently exporting files (Endpoint can send updated numbers)</li>
<li>Must have ability to download import files from admin side (Sylius back-office)</li>
<li>Must have ability to download export files from admin side (Sylius back-office)</li>
<li>Must send emails when import is complete</li>
<li>Must send emails when export is complete</li>
<li>Must support same functionality for Vendors, just for their access scope only (not required)</li>
<li>Admins must see all file export and have ability to filter them out by user, vendor etc. (not required)</li>
<li>Must have ETA when file is being export or imported shown to the user (Endpoint can send updated numbers)</li>
<p>Can be used any library, just add needed author as per license required.</p>
<p>Code testing - required.</p>
<p>Comments in code - required.</p>
<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Sylius Standard Edition</h1>

<p align="center">This is Sylius Standard Edition repository for starting new projects.</p>

About
-----

Sylius is the first decoupled eCommerce platform based on [**Symfony**](http://symfony.com) and [**Doctrine**](http://doctrine-project.org). 
The highest quality of code, strong testing culture, built-in Agile (BDD) workflow and exceptional flexibility make it the best solution for application tailored to your business requirements. 
Enjoy being an eCommerce Developer again!

Powerful REST API allows for easy integrations and creating unique customer experience on any device.

We're using full-stack Behavior-Driven-Development, with [phpspec](http://phpspec.net) and [Behat](http://behat.org)

Documentation
-------------

Documentation is available at [docs.sylius.com](http://docs.sylius.com).

Installation
------------

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar create-project sylius/sylius-standard project
$ cd project
$ yarn install
$ yarn build
$ php bin/console sylius:install
$ php bin/console server:start
$ open http://localhost:8000/
```

Troubleshooting
---------------

If something goes wrong, errors & exceptions are logged at the application level:

```bash
$ tail -f var/log/prod.log
$ tail -f var/log/dev.log
```

If you are using the supplied Vagrant development environment, please see the related [Troubleshooting guide](etc/vagrant/README.md#Troubleshooting) for more information.

Contributing
------------

Would like to help us and build the most developer-friendly eCommerce platform? Start from reading our [Contribution Guide](https://docs.sylius.com/en/latest/contributing/)!

Stay Updated
------------

If you want to keep up with the updates, [follow the official Sylius account on Twitter](http://twitter.com/Sylius) and [like us on Facebook](https://www.facebook.com/SyliusEcommerce/).

Bug Tracking
------------

If you want to report a bug or suggest an idea, please use [GitHub issues](https://github.com/Sylius/Sylius/issues).

Community Support
-----------------

Have a question? Join our [Slack](https://slackinvite.me/to/sylius-devs) or post it on [StackOverflow](http://stackoverflow.com) tagged with "sylius". You can also join our [group on Facebook](https://www.facebook.com/groups/sylius/)!

MIT License
-----------

Sylius is completely free and released under the [MIT License](https://github.com/Sylius/Sylius/blob/master/LICENSE).

Authors
-------

Sylius was originally created by [Paweł Jędrzejewski](http://pjedrzejewski.com).
See the list of [contributors from our awesome community](https://github.com/Sylius/Sylius/contributors).

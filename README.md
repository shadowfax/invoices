Invoices
========

Introduction
------------
This is a simple invoicing application powered by Zend Framework 2 (ZF2).

Installation
------------

Using Composer (recommended)
----------------------------
The recommended way to get a working copy of this project is to clone the repository
and manually invoke `composer` using the shipped `composer.phar`:

    cd my/project/dir
    git clone git://github.com/shadowfax/invoices.git
    cd invoices
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Virtual Host
------------
Afterwards, set up a virtual host to point to the public/ directory of the
project.

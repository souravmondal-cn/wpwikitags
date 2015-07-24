Wordpress Wiki Plugin
======================
About Me: [Sourav Mondal]<a></a>

  [Sourav Mondal]: http://souravmondal.co.in/resume


This plugin is aimed to convert all abbreviation tags within a WordPress
post or page into corresponding Wikipedia links.

Requirements
------------

-   Minimum Wordpress Version: 3.8
-   Minimum PHP Version: 5.4
-   PHP DomDocument Library

Installation Guide
------------------

 - Way 1: Clone this repository inside wp-content/plugins directory, goto wp admin admin activate this plugin from plugins list.
 - Way 2: Download this repository somewhere in your computer from github and paste the folder inside the wp-content/plugins directory.or upload the directory via ftp.


Settings page of the Admin
------------------

 - Log into the wp admin panel.
 - The settings page is under main Settings menu.
 - Goto the menu called `Wiki Links Settings`.

Development guide(LAMP Stack)
------------------

to initialize the development environment run
```bash
    make
```

To check the code quality on PSR2 standard
```bash
    make check
```

To generate PhpDocs run (docs generated inside the phpDocs directory)

```bash
    make phpdoc
```

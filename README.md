YiiFrame plug-in Center
=======================
The plugin center based on YiiFrame supports online installation and uninstallation of plugins.

[![Latest Stable Version](http://poser.pugx.org/yii2framework/yiiframe-plugs/v)](https://packagist.org/packages/yii2framework/yiiframe-plugs) [![Total Downloads](http://poser.pugx.org/yii2framework/yiiframe-plugs/downloads)](https://packagist.org/packages/yii2framework/yiiframe-plugs) [![Latest Unstable Version](http://poser.pugx.org/yii2framework/yiiframe-plugs/v/unstable)](https://packagist.org/packages/yii2framework/yiiframe-plugs) [![License](http://poser.pugx.org/yii2framework/yiiframe-plugs/license)](https://packagist.org/packages/yii2framework/yiiframe-plugs) [![PHP Version Require](http://poser.pugx.org/yii2framework/yiiframe-plugs/require/php)](https://packagist.org/packages/yii2framework/yiiframe-plugs)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2framework/yiiframe-plugs "*"
```

or add

```
"yii2framework/yiiframe-plugs": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

http://www.demo.com/plugs

Plugin market (1.2.0+)
----------------------

* `/plugs` opens the market: plugin cards with search, category, price, install-state filters and sorting.
  Old routes `plugs/addons/plugs|free|pay` still work.
* Market categories (`?cate=<id>`) come from the auth server (Plug addon 1.4.0+, cached 10 minutes) and are
  separate from a plugin's `group`, which still decides where the plugin appears in the site's backend menu.
  With an older auth server the market falls back to filtering by `group`.
* The nav shows "N 个可升级" when the market has newer versions of installed plugins; it links to the market
  filtered to upgradable plugins, and the installed list marks each one.
* Plugin detail page `/plugs/<Name>` (or `plugs/addons/view?name=`): screenshots, introduction,
  online demos (frontend / backend / H5 / mini program, with demo account and QR code),
  version history with changelogs, requirements (framework, dependencies with local install state),
  install / upgrade / choose version / buy / uninstall. Local plugins not in the market get a detail page from `AddonConfig`.
* Installed list: when the plugin code (`AddonConfig` version) is newer than the installed version,
  "Upgrade" runs the plugin's `Upgrade` scripts and reloads its config (`plugs/addons/upgrade-local`).
* Developer upload (`plugs/addons/upload`), after binding a market account:
  1. upload a zip, or pack a local plugin in `addons/` (skips `.git`, `node_modules`, `runtime`, `.license`);
     the auth server stores it privately, checks the structure and parses `AddonConfig` without running code;
  2. fill in the listing (prefilled from the package): screenshots, introduction, demos, changelog;
  3. submit for review; results and review comments are in "My uploads" (`plugs/addons/my-uploads`), pending ones can be withdrawn.
* My orders (`plugs/addons/my-orders`), after binding a market account: the member's paid plugin orders
  (amount, bound domain, order number, payment time), whether each one is installed on this site or bound to another domain,
  with a link to the plugin page. Needs the auth server's Plug addon 1.4.1.
* All communication with the auth server is server side; the member token is never written into pages.
  Introductions are filtered with HtmlPurifier before display.
* Needs the auth server's Plug addon 1.3.0 for the detail / upload APIs (1.4.0 for market categories); with an older auth server
  the detail page falls back to the old `introduce` API.

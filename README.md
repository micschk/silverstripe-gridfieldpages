Manage pages from a gridfield instead of the sitetree
=====================================================

This module tackles the issue of a cluttered SiteTree by managing pages from a gridfield. It can easily handle thousands of subpages (production-tested with 65.000 pages under one parent).

This module is meant as base classes, it can be used on its own but usually you will want to subclass in order to add filtering/sorting, etc. An example module subclassing this module is Newsgrid (filterable newsitems managed from a gridfield).


## Features

* GridFieldPages extend SiteTree so no Page functionality is lost by using DataObjects.
* Custom GridField components for quickly adding new pages.
* Hides sub pages from the sitetree (via excludechildren).
* Drag 'n drop sorting of pages (loop over $SortedChildren in templates).


## Requirements

 * SilverStripe 3.0 or newer
 * [silverstripe-excludechildren module to hide pages from the sitetree](https://github.com/micschk/silverstripe-excludechildren)
 * [silverstripe-gridfieldsitetreebuttons to manage SiteTree items in a gridfield](https://github.com/micschk/silverstripe-gridfieldsitetreebuttons)

*These will get auto-installed when using composer*


## Installation

```
composer require micschk/silverstripe-gridfieldpages dev-master
```


## Screenshots

*Easily manage and add new pages through a GridField.*
![](images/screenshots/holderscreen.png)

*Edit pages in the regular editform, including settings, history & versioning.*
![](images/screenshots/editscreen.png)


## Recommended

Manage newsitems from a gridfield, with embargo & expire (auto-publishing) functionality
* [silverstripe-newsgrid](https://github.com/micschk/silverstripe-newsgrid)
# Augustash_FixUserAclResourceTree

## Overview:

This modules is a stop-gap solution to offer a fix for the issue of fetching the User Role ACL Resources to generate the checkbox tree to allow admins to restrict access to chosen resources.

See the following Github issues:

+ [magento/magento2#7101](https://github.com/magento/magento2/issues/7101)
+ [magento/magento2#7146](https://github.com/magento/magento2/issues/7146)


## Installation

In your project's `composer.json` file, add the following lines to the `require` and `repositories` sections:

```js
{
    "require": {
        "augustash/module-fixuseraclresourcetree": "dev-master"
    },
    "repositories": {
        "augustash-fixuseraclresourcetree": {
            "type": "vcs",
            "url": "https://github.com/augustash/magento2-module-fixuseraclresourcetree.git"
        }
    }
}
```

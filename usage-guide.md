# Usage Guide & Configuration

This guide explains how to configure the `hussainas-admin-menu-manager.php` snippet to hide the exact menu items you want.

All configuration is done by editing the "CONFIGURATION" functions found at the top of the `hussainas-admin-menu-manager.php` file, or by using the built-in filters.

---

### 1. How to Configure Restricted Roles

Edit the `hussainas_get_restricted_roles()` function to control *who* is affected.

**Default:**
```php
function hussainas_get_restricted_roles() {
    return array(
        'editor',
        'author',
        'subscriber',
        'contributor',
    );
}

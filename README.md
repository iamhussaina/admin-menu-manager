# Hussain's Admin Menu Manager

A simple, procedural PHP code snippet for WordPress to hide specific admin menu items based on user roles.

This code is designed to be included in a theme (or as a must-use plugin) to restrict what client-level users (like 'Editors' or 'Authors') can see in the WordPress admin dashboard, preventing them from accessing sensitive areas like 'Settings' or 'Tools'.

**This is not a plugin.** It is a single-file snippet intended for developers to bundle with their themes or use as a utility.

## Features

* **Role-Based Control:** Easily define which user roles to restrict.
* **Flexible Hiding:** Hide entire top-level menus (e.g., Tools) or specific submenu items (e.g., Appearance -> Themes).
* **Easy Configuration:** All settings are managed in dedicated configuration functions at the top of the file.
* **Developer Friendly:** Uses `apply_filters` so settings can be overridden from other files (like a theme's `functions.php`) without editing the snippet directly.
* **Procedural Code:** Written in simple, procedural PHP. No classes or OOP.
* **Lightweight:** No overhead. Just a single function hooked into `admin_menu`.

---

## 🚀 Installation & Usage

There are two primary ways to use this code:

### 1. Include in Your Theme (Recommended)

1.  Copy the `hussainas-admin-menu-manager` directory into your theme's folder. For example: `wp-content/themes/your-theme/includes/hussainas-admin-menu-manager`.
2.  Open your theme's `functions.php` file.
3.  Add the following line to include the snippet:

    ```php
    // Load the admin menu manager
    require_once get_template_directory() . '/includes/hussainas-admin-menu-manager/hussainas-admin-menu-manager.php';
    ```
    *(Adjust the path based on where you placed the directory).*

### 2. Use as a Must-Use Plugin (MU-Plugin)

This is a great option for applying this to a site permanently, regardless of the active theme.

1.  Find (or create) the `mu-plugins` directory in `wp-content/mu-plugins/`.
2.  Copy the main file, `hussainas-admin-menu-manager.php` (just the single file), into this directory.
3.  That's it. As an MU-plugin, it will be loaded automatically.

---

## ⚙️ Configuration

All configuration is done by editing the functions at the top of the `hussainas-admin-menu-manager.php` file.

See the **[Usage Guide & Configuration](usage-guide.md)** for detailed instructions on how to find menu slugs and customize the snippet to your needs.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

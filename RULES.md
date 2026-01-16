# Project Rules and Guidelines

## Project Overview

This is a custom PHP web application for a fashion e-commerce site ("Web Man Fashion"). It allows users to browse products, select sizes/colors, and place orders. It includes a frontend for users and an admin panel (in `admin/`) for management.

## Technology Stack

- **Language**: PHP (Procedural/Mixed with HTML).
- **Database**: MySQL (accessed via `mysqli` extension).
- **Frontend**: HTML5, CSS3, Bootstrap 3 (implied by `glyphicon` and file structure), jQuery.
- **Server**: Apache/Nginx (assumed standard PHP setup).

## Architecture

- **Page-Controller Pattern**: Each functionality corresponds directly to a `.php` file in the root directory (e.g., `dang-nhap.php` for login, `product.php` for viewing a product).
- **Database Connection**: Managed in `inc/myconnect.php`. The connection variable `$dbc` is global and used throughout the application.
- **Helper Functions**: Common logic is stored in `inc/function.php`.

## Coding Conventions

### File Organization

- **Root**: usage-specific pages (e.g., `index.php`, `contact.php`).
- **`inc/`**: Backend logic includes (database connection, functions).
- **`include/`**: UI partials (header, footer).
- **`admin/`**: Administrative interface.
- **`css/`, `js/`, `fonts/`**: Static assets.
- **`vendor/`**: Composer dependencies (e.g., PHPMailer).

### Naming Conventions

- **Files**: Lowercase, hyphen-separated. Vietnamese names are often used (e.g., `dang-ky.php`, `chinh-sach-bao-mat.php`).
- **Variables**: snake_case (e.g., `$query_sp`, `$id_category`).
- **Database**:
  - Tables: `tb_` prefix (e.g., `tb_san_pham`, `tb_danh_muc`).
  - Columns: snake_case (e.g., `ten_san_pham`, `gia_khuyen_mai`).
- **Functions**: snake_case or camelCase (inconsistent, but prefer snake_case for new functions to match majority like `category_name`, `ramdom_code`).

### Database Interaction

- Use `global $dbc;` inside functions to access the database.
- Use `mysqli_query($dbc, $sql)` for queries.
- ALWAYS use `kt_query($result, $query)` (from `inc/function.php`) after a query to handle errors during development.
- Data fetching loop pattern:
  ```php
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      // process $row
  }
  ```

### key Functions (in `inc/function.php`)

- `kt_query($result, $query)`: Check query success.
- `stripUnicode($str)`: Remove accents/spaces for slugs/codes.
- `ramdom_code()`: Generate unique order code.
- `lay_id($parent_id)`: Get recursive children IDs for categories.

## Development Rules

1. **Preserve Structure**: Do not introduce a framework (like Laravel) into this existing codebase unless explicitly asked. Stick to the native PHP/mysqli pattern.
2. **Vietnamese Localization**: The project uses Vietnamese purely. Ensure new UI text and variable names (where appropriate for context) align with this.
3. **Global State**: Be aware of `global $dbc`. Ensure it's available in any new included files or functions.
4. **Security**: Note that raw `mysqli` queries are used. When adding new features, prefer prepared statements where possible, or strictly sanitize inputs to prevent SQL Injection, though the existing codebase uses direct string interpolation in many places.
5. **Formatting**: Maintain the existing indentation (looks like 4 spaces or tabs - check current files).

## Workflow

1. Create new page `.php` file or modify existing.
2. Include `inc/myconnect.php` and `inc/function.php` at the top.
3. Include `include/header.php`.
4. Implement logic.
5. Include `include/footer.php`.

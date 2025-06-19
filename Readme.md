# Vendor_CustomOrderProcessing

## Overview

**Vendor_CustomOrderProcessing** is a Magento 2 module that provides a custom REST API endpoint for updating order statuses, logs all order status changes to a custom database table, and sends email notifications to customers when their order is marked as shipped. The module follows Magento 2 best practices, including dependency injection, repository usage, and event-driven architecture.

---

## Features

- **Custom REST API** to update order status by increment ID.
- **Order status validation** and error handling.
- **Event observer** for `sales_order_save_after` to:
  - Log status changes (order ID, old status, new status, timestamp) to a custom table.
  - Send a dynamic email notification to the customer when the order is marked as shipped.
- **Follows Magento 2 coding standards** (PSR-4, DI, no direct ObjectManager usage).
- **Optimized for performance** using repository patterns and efficient queries.

---

## Installation

1. Copy the module to `app/code/Vendor/CustomOrderProcessing`.
2. Run the following Magento CLI commands:
    ```bash
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    php bin/magento cache:flush
    ```
3. Ensure file permissions are set correctly for your environment.

---

## API Usage

### Endpoint

`POST /rest/V1/customorder/update-status`

### Authentication

- Use a valid Magento Bearer token (integration/admin).

### Request Body

```json
{
  "incrementId": "100000001",
  "status": "processing"
}
```

### Response

- Returns the updated order object or an error message.

---

## Event Observer

- Listens to `sales_order_save_after`.
- Logs every order status change to the `vendor_customorderprocessing_log` table.
- Sends an email notification to the customer if the order status is changed to `shipped`.

---

## Email Template

- The module uses a dynamic email template identifier (default: `order_shipped_notification`).
- Create or customize the template in **Marketing > Communications > Email Templates** in the Magento admin.

---

## Custom Database Table

- Table: `vendor_customorderprocessing_log`
- Columns: `log_id`, `order_id`, `old_status`, `new_status`, `created_at`

---

## Extending the Module

- You can customize the API, observer logic, and email templates as needed.
- The module is structured for easy extension and maintenance.

---

## Coding Standards

- PSR-4 autoloading.
- Dependency Injection (DI) for all services.
- No direct use of ObjectManager.
- Uses Magento repositories for order operations.

---

## Support

For questions or issues, please open an issue or contact the module author.

---
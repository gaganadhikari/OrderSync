# Project Information

This project is implemented using Laravel 11.

## Getting Started

To run the application, execute the following command:
```./vendor/bin/sail up```


## Branch Information

This repository only contains one branch: `master`.

## Environment Configuration

Ensure to add your environment variables by copying `.env.example` to `.env`.

## Implementation Details

### 1. Sync Orders

- Implemented an action `SyncOrder` within `App\Actions`, featuring a `handle` function accepting a `days` argument.
- Created a console command class `SyncOrders` which invokes the `SyncOrder` action. The command is `app:sync-orders`, with a parameter of 30 days.
- Developed a scheduled task in `routes/console` set to run daily at 12 PM.

### 2. REST API

#### Fetch Orders Endpoint

Implemented a REST API endpoint `/api/order` with the following query parameters:

- **Filtering**:
    - `status`: Accepts values `processing` or `completed`.
    - `order_id`: Accepts order numbers (e.g., 220, 219).
    - `customer_id`: Accepts customer id values like `0`, `1`.
    - `from_date` and `to_date`: Accepts date values.

- **Sorting**:
    - `sort_by`: Accepts values `number`, `date_created`, `created_at`, `updated_at`, or `id`.
    - `sort_direction`: Accepts values `desc` or `asc`.

- **Search**:
    - `search`: Accepts search text.

- **Pagination**:
    - `per_page`: Sets the number of results per page (default is 10, can be overridden).

#### Data Sync Endpoint

Implemented another endpoint `/api/order/sync` to sync data from the last day since the data sync is performed daily via a cron job.

### 3. Cleanup Command

Implemented a command `delete:unused-order` within `routes/console` to delete orders that haven't been updated in the last 3 months.



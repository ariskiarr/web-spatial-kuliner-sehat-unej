# Login Credentials

After running `php artisan migrate:fresh --seed`, you can use these credentials:

## Admin Account

-   **Email**: admin@example.com
-   **Password**: password
-   **Role**: admin
-   **Access**: Dashboard Admin (with management features)

## Regular User Account

-   **Email**: user@example.com
-   **Password**: password
-   **Role**: user
-   **Access**: Dashboard User (read-only features)

---

## Creating New Users

When registering new users via the registration form:

-   Default role is automatically set to: **user**
-   New users will be redirected to the User Dashboard
-   To create admin users, you need to manually update the database or use tinker:

```php
php artisan tinker
> $user = App\Models\User::where('email', 'someone@example.com')->first();
> $user->role = 'admin';
> $user->save();
```

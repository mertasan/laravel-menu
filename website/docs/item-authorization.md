# Item Authorization

- [Item Authorization](#item-authorization)
    - [Guests Only Menu Item](#guest-only-menu-item)
    - [Menu Item for Logged-in Users Only](#menu-item-for-loggedin-users-only)
    - [Admin Only Menu Item](#admin-only-menu-tem)
- [Custom Authorization Controls](#custom-authorization-controls)
- [Conditional Transactions](#conditional-transactions)

<a name="item-authorization"></a>
## Item Authorization

[Laravel Authentication](https://laravel.com/docs/8.x/authentication) is used for basic authorization controls (user / guest). And [Laravel Jetstream](https://jetstream.laravel.com/2.x/features/teams.html#inspecting-user-teams) is used for other authorization controls.

<a name="guest-only-menu-item"></a>
### Guests Only Menu Item

```php
$menu->add('Login')->onlyGuests();
```

<a name="menu-item-for-loggedin-users-only"></a>
### Menu Item for Logged-in Users Only

```php
$menu->add('Logout')->onlyUsers();
```


<a name="admin-only-menu-item"></a>
### Admin Only Menu Item

```php
$menu->add('Settings')->onlyAdmins();
```

<a name="custom-authorization-controls"></a>
## Custom Authorization Controls

```php
$menu->add('Settings')->permission(function($user) {
    return $user->hasTeamPermission($user->currentTeam, 'management:settings');
});

$menu->add('General Settings')->permission(function($user) {
    return $user->id < 2;
});
```

<a name="conditional-transactions"></a>
## Conditional Transactions

```php
$menu->add('Models');
$menu->add('Settings')->onlyAdmins();

if($menu->settings->isAllowed()){
    $menu->models->add('Products');
}

if($menu->settings->isAllowed(\App\User::find(10))){
    $menu->models->add('Categories');
}
```

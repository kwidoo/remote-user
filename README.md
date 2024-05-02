# Remote User Packag

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kwidoo/remote-user.svg?style=flat-square)](https://packagist.org/packages/kwidoo/remote-user)
[![Total Downloads](https://img.shields.io/packagist/dt/kwidoo/remote-user.svg?style=flat-square)](https://packagist.org/packages/kwidoo/remote-user)
![GitHub Actions](https://github.com/kwidoo/remote-user/actions/workflows/main.yml/badge.svg)

This package simplifies integration with an IAM server within a microservices architecture by fetching the logged-in remote user's information. It ensures security by returning only one record per authorized user. While primarily designed for use with IAM servers, it is also compatible with other OAuth2 servers. The user data is expected in a simple array format from the user endpoint, with `uuid` as the mandatory field.

## Requirements

- Laravel Sanctum
- User model should utilize the `AsRemoteUser` trait and implement the `RemoteUser` contract.

## Installation

Install the package via Composer:

```bash
composer require kwidoo/remote-user
```

Optionally, publish the configuration file:

```bash
php artisan vendor:publish --tag=remote-user-config
```

## Configuration

Add the following IAM server's OAuth2 credentials to your `.env` file:

```env
IAM_SERVER_CLIENT_ID=your-client-id
IAM_SERVER_CLIENT_SECRET=your-client-secret
IAM_SERVER_URL=your-iam-server-url
```

Ensure your `auth.php` file is configured with the appropriate guards and providers to use with this package:

```php
'guards' => [
    'api' => [
        'driver' => 'sanctum',
        'provider' => 'remote_users',
    ],
],
'providers' => [
    'remote_users' => [
        'driver' => 'remote',
        'model' => App\Models\User::class, // or you model
    ],
],
```

These settings configure Laravel to use Sanctum with the remote user model, ensuring proper authentication handling through the IAM server.

You can specify an alternative user model in the configuration file:

```php
    'user_class' => App\Models\User::class  // or your model
```

## Usage

This package facilitates the following workflow in conjunction with an IAM server and local Laravel Sanctum:

1. The frontend obtains a password grant and opaque token from the IAM server.
2. The frontend sends the opaque token to this package.
3. The package obtains a client credentials grant from the IAM server.
4. By using the opaque token along with the access token from step 3, it fetches the remote user from the IAM server.
5. If the user is successfully fetched, it provides a Sanctum token to the frontend.

## Obtaining Sanctum Token

To obtain a Sanctum token, make a GET request to the `/sanctum/token` endpoint. You can change the route as needed:

```php
Route::get('/sanctum/token', RemoteUserController::class . '@token');
```

## Troubleshooting

If you encounter issues accessing the `/sanctum/token` route, use:

```bash
php artisan route:list
```

to verify the exact route.

### Testing

Run tests using:

```bash
composer test
```

### Changelog

For recent changes, please refer to the [CHANGELOG](CHANGELOG.md).

## Contributing

For contribution guidelines, please see [CONTRIBUTING](CONTRIBUTING.md).

### Security

For security-related issues, please contact oleg@pashkovsky.me directly rather than using the public issue tracker.

## Credits

- [Oleg Pashkovsky](https://github.com/kwidoo)
- [All Contributors](../../contributors)

## License

This package is licensed under the MIT License. See the [License File](LICENSE.md) for more details.

## Additional Information

This package was developed using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).

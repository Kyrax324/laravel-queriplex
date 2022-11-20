# Laravel Queriplex

A simple package to help building query builder between request inputs and allowed rules.

## Installation

```sh
composer require kyrax324/laravel-queriplex
```

## Usage
### Generating Queriplex Classes

Generate a queriplex class for `User` model

```sh
php artisan make:queriplex UserQueriplex
```

** You may also publish the stub template file for customize purpose.

```sh
php artisan queriplex:publish-stub
```

### Setup Filter & Sort Rules

Fill up the necessarily filtering and sorting rules in the `UserQueriplex.php`

Example:

```php

public $sortingKey = "sortBy"; // #1

public function filterRules()
{
	return [
		// with callback function
		'country' => function($query, $value){
			$query->where("country_code", $value);
		},
		// with shortcut alias
		'country' => 'country_code', // when isset 'country', where "country_code" = $value
	];
}

public function sortRules()
{
	return [
		"alphabet_asc" => fn ($query) => $query->orderBy('name', "ASC"),
		"alphabet_desc" => fn ($query) => $query->orderBy('name', "DESC"),
		"latest" => fn ($query) => $query->orderBy('created_at', "DESC"),
	];
}
```

## Use Case Example

To get a result of users where country_code = "ABC" order by latest created time

`UserController.php`

```php
use App\Queriplex\UserQueriplex;

...

// payload from request->validated()
$payload = [
	"country" => "ABC", // similar to when(isset(payload['country']), $callback)
	"sortBy" => "latest", // sortingKey #1
];

$query = User::query();
$query = UserQueriplex::make($query, $payload);

$result = $query->get();

/**
 * As result,
 * filter rule "country_code" & sort rule "latest" will be applied
 *
 * SQL Statement:
 * SELECT * from `users`
 * WHERE `country_code` = "ABC"
 * ORDER BY `created_at` DESC
 */

```


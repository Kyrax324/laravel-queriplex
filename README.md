# Laravel Queriplex

A simple package to help building query builder between request inputs and allowed rules.

## Installation

```bash
composer required kyrax324/laravel-queriplex
```

## Available Methods

### #Filter()

|   | Type | Description |
|---|---|---|
| @param_1 | Illuminate\Database\Eloquent\Builder | QueryBuilder to be build |
| @param_2 | array | Requests |
| @param_3 | array | Allowed rules |
| @return | Illuminate\Database\Eloquent\Builder | - |


Example:

```php
	$request = [
		"name" => 'abc',
		"role_id" => '1',
		"type" => '2' // will be ignore
		"company_type" => "3"
	];

	$users = Queriplex::filter( User::query(), $request, [
		'name' => "username", // will be convert to "$query->where('username',$value)"
		'role_id' => 'role_id', // will be convert to "$query->where('role_id',$value)"
		'company_type' => function($query, $value){ // callback
			return $query->whereHas('company',function($q) use ($value){
				return $q->where('type', $value);
			});
		},
	]);

	$result = $users->get();

```
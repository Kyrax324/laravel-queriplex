# Laravel Queriplex

A simple package to help building query builder between request inputs and allowed rules. 

- Filter and Sort

## Installation

```sh
composer require kyrax324/laravel-queriplex
```

## Available Methods

### # make()

Set the query builder.

|   | Type | Description |
|---|---|---|
| @param_1 | Illuminate\Database\Eloquent\Builder | $query |
| @return | $this | - |

### # withFilters()

Set the filterables rules.

|   | Type | Description |
|---|---|---|
| @param_1 | array | $requests |
| @param_2 | array | $filterRules |
| @return | $this | - |

### # withFilters()

Set the sortable rules.

|   | Type | Description |
|---|---|---|
| @param_1 | string|null | $key |
| @param_2 | array | $sortRules |
| @return | $this | - |

### # apply()

Apply the queriplex logic to query.

|   | Type | Description |
|---|---|---|
| @return | Illuminate\Database\Eloquent\Builder | - |

---

### # getFilterables()

Get related array of filtering rules.

|   | Type | Description |
|---|---|---|
| @return | array | $filterables |


### # getSortable()

Get related sorting rule.

|   | Type | Description |
|---|---|---|
| @return | string|null | $sortable |

## Example:

```php
$request = [
	"name" => 'abc',
	"role_id" => '1',
	"type" => '2' // will be ignore
	"company_type" => "3"
];

$query = User::query();

$queriplex = Queriplex::make($query)
	->withFilters($request,[
		'name' => "username", // will be convert to "$query->where('username',$value)"
		'role_id' => 'role_id', // will be convert to "$query->where('role_id',$value)"
		'company_type' => function($query, $value){ // callback
			return $query->whereHas('company',function($q) use ($value){
				return $q->where('type', $value);
			});
		},
	])
	->withSort($request['sortBy'] ?? null, [
		"id" => fn($query) => $query->orderBy('id', "ASC"), // php 7.4 - arrow function 
		"total_credit" => function($query){
			return $query->orderBy('total_credit', "DESC")->orderBy('id');
		},
	]);

$user = $queriplex->apply(); // apply queriplex logic and return query builder of User Model

$result = $user->get();

```
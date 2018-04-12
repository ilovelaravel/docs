# API Documentation

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This package contains the documentation generator & documentation viewer.
The packaged reads json from a folder and turns it into a website with api info. It also includes a generator.

![screenshot](https://github.com/ilovelaravel/docs/blob/master/screenshot.png)

## Installation

```composer require "ilovelaravel/docs":"dev-master"```

### Install via composer:


### Basis setup
Publish config

```php artisan vendor:publish```

## Usage

All files needed are stored in ```storage/docs```. The main file is ```storage/docs/api.json```.

All generated files live in ```storage/docs/generated```. Copy them over to ```storage/docs``` after generation.

Run the following command to generate the files:

```php artisan api:docs:generate ```

The result will be placed in the ```storage/docs/generated``` folder.

There you will find the following structure:

```bash
# Main file which structure we use to create the views
/api.json    

# Example request + response objects
/examples
/examples/<collection_name>/responses/named.route.json
/examples/<collection_name>/requests/named.route.json

# Examples of headers for the requests
/headers

# All the resources(GET,POST,PUT,DELETE) per collection 
/resources
/resources/<collection_name>.json
```

### Routes preparation:
The generator only looks at named routes starting with ```api```. Like this: ```->name(api.something.something)```
The generator can extract permissions from routes that use the ```can``` middleware. Like this: 

```php
$this->get('users', 'UsersController@index')->name('api.users.index')->middleware('can:users.read'); 
```

The frontend views are currently showing a menu, which can be build by preparing the routes like this:

```php
# group names turn in to menu items. Below 'general', will be the parent. 
# collection will be the name of the child menu name.
$this->group(['group'=>'general','collection'=>'users'],
    function() {
        $this->get('users',      'Crud\usersController@index')->name('api.users.index')->middleware('can:users.read');
        $this->post('users',     'Crud\usersController@store')->name('api.users.store')->middleware('can:users.store');
    });
$this->group(['group'=>'general','collection'=>'dogs'],
    function() {
        $this->get('dogs',       'Crud\dogsController@index')->name('api.dogs.index')->middleware('can:dogs.read');
        $this->post('dogs',      'Crud\dogsController@store')->name('api.dogs.store')->middleware('can:dogs.store');
    });        

$this->group(['group'=>'addresses','collection'=>'companies'],
    function() {
        $this->get('companies',  'Crud\companiesController@index')->name('api.companies.index')->middleware('can:companies.read');
        $this->post('companies', 'Crud\companiesController@store')->name('api.companies.store')->middleware('can:companies.store');
    });
```

This will result in a menu looking like this:

General
    Users
    Dogs
Addresses
    Companies

### Controller preparation
Annotations should be placed on controller methods.
- ```@NoAuth``` For methods that do not need ```authentication```
- ```@Title("Show all users")``` A title that describes the api action
- ```@Info("This will return all users")``` Some information about the action

### Api specific Form requests
 
 If you create new form requests, for new api actions, then you could use the artisan command:

 ```docs:make:request AddUserRequest```
 
 If you update existing form request objects, then you should add the ```Guarded``` trait and implement the  ```ApiRequestContract``` contract.
 
 Both the ```rules()``` and the ```schema``` method should at least return an empty array;
 
 ```php

// Regular request validation rules. Please always fill this completely,  
// also for optional(nullable) fields

public function rules() 
{
    return [
        'first_name' => 'required|string'
    ]
}


// This will show up in the documentation. The format is:
// array('parameter key','validation rules','example of expected input') 

public function schema() 
{
    // Faker can be used to generated example output
    $faker = \Faker\Factory::create();
    
    return [
        ['first_name','required|string', $faker->firstName], // use faker like this
        ['key','validation','example'], // etc etc
        ['key','validation','example']  // etc
    ]
}

``` 

### Responses

The structure of the folder ```examples``` looks like this::
 
 ```
 examples/
 example/requests/named.route.namespace.json
 example/responses/named.route.namespace.json 
 ```
 This should be your main folder. Configure this in your config. All generated files will be stored in the ```docs/generated``` folder. Copy files from there into the main folder. This way you can preview changes in without losing information that is allready generated before.

### Routes command
```php artisan docs:routes```
Will give you a clean preview of the routes that will be used

![screenshot](https://github.com/ilovelaravel/docs/blob/master/screenshot2.png)

### Views
The documentation can be viewed in the browser: ```http(s)://your-url.something/docs```

 If you like to edit the views, you need to publish them. After publishing the views for the templates can be found at:
 ```resources/views/docs```
 
 I went fast & wonderfull to create this generator. The least important part for me were the views, so they contain a 
 lovely hackjob of inline javascript an some css, which could be refactored, if you care. For me it has absolutely no
 priority.

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing
You can run this command to see that nothing happens. Maybe some errors. 

```bash
composer test
```

## Credits

- [Xander Smalbil](http://videofunk.nl)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
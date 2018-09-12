<?php

//use Faker\Generator as Faker;
use Faker\Factory as Factory;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

//$factory->define(App\Models\User::class, function (Faker $faker) {
$factory->define(App\Models\User::class, function () {
    $faker = Factory::create('zh_CN');
	$date_time = $faker->dateTime();
	static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password?$password:$password =  bcrypt('secret'), // secret
        'remember_token' => str_random(10),
        'created_at'=>$date_time,
        'updated_at'=>$date_time,
    ];
});

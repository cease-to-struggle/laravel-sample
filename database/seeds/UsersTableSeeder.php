<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(User::class,100)->create();
        $user = User::find(1);
        $user->name = 'root';
        $user->email = 'root@email.com';
        $user->password = bcrypt('secret');
        $user->is_admin = true;
        $user->activated = true;
        $user->save();
        
    }
}

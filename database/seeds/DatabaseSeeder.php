<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StatesTableSeeder::class);
        $this->call(RegionesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserRolTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(ProvinciasTableSeeder::class);
        $this->call(PostTableSeeder::class);
        $this->call(PhotoTableSeeder::class);
        $this->call(UserPostLikeTableSeeder::class);
        $this->call(PostTagsTableSeeder::class);
    }
}

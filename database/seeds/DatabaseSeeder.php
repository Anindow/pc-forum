<?php

use App\Models\Brand;
use App\Models\District;
use App\Models\Division;
use App\Models\Shop;
use App\Models\Tag;
use App\Models\Upazila;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Category;
use App\Models\Setting;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Role::truncate();
        Permission::truncate();
        Category::truncate();
        Setting::truncate();
//        Division::truncate();
//        District::truncate();
//        Upazila::truncate();
        Tag::truncate();
        Brand::truncate();
        Shop::truncate();

        $this->call(SettingSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
//        $this->call(DivisionSeeder::class);
//        $this->call(DistrictSeeder::class);
//        $this->call(UpazilaSeeder::class);

        //for Admin
        $admin = factory(User::class)->create([
            'first_name' => 'Admin',
            'last_name' => '',
            'email' => 'admin@mail.com',
            'phone' => '0123456789',
            'avatar' => 'default-profile.png',
            'is_admin' => 1,
            'password' => bcrypt(123456)
        ]);
        $admin->assignRoles(['admin', 'manager', 'editor']);

        //for editor
        $editor = factory(User::class)->create([
            'first_name' => 'Manager',
            'last_name' => '',
            'email' => 'manager@mail.com',
            'password' => bcrypt(123456),
            'is_admin' => 1,
        ]);
        $editor->assignRole('manager');

        //for editor
        $author = factory(User::class)->create([
            'first_name' => 'Editor',
            'last_name' => '',
            'email' => 'editor@mail.com',
            'password' => bcrypt(123456),
            'is_admin' => 1,
        ]);
        $author->assignRole('editor');

//        factory(User::class, 10)->create();

        $permissions = Permission::all();
        $permissions->each(function ($permission) {
            $role_admin = Role::where('name', 'admin')->first();
            $role_admin->givePermissionTo($permission);
        });

        // Others
        factory(Category::class, 4)->create(['category_id' => null]);
        factory(Category::class, 15)->create();
        factory(Tag::class, 8)->create();
        factory(Brand::class, 6)->create();
        factory(Shop::class, 10)->create();

    }
}

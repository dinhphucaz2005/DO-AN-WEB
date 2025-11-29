<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = "admin123@gmail.com";
        $exists = User::where("email", $email)->first();
        if ($exists) {
            $exists->update(["is_admin" => true]);
            $this->command->info(
                "Admin user already exists. Set is_admin=1 for {$email}",
            );
            return;
        }

        User::create([
            "name" => "Admin",
            "email" => $email,
            "password" => Hash::make("12345678"),
            "is_admin" => true,
        ]);

        $this->command->info("Admin user created: " . $email);
    }
}

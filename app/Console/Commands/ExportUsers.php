<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use League\Csv\Writer;

class ExportUsers extends Command
{
    protected $signature = 'export:users {--filename=users.csv : The name of the CSV file to generate}';

    protected $description = 'Export all users to a CSV file';

    public function handle()
    {
        $filename = $this->option('filename');
        $users = User::all();

        $csv = Writer::createFromPath(storage_path("app/{$filename}"), 'w+');
        $csv->insertOne(['ID', 'Name', 'Date of Birth', 'Age', 'Phone Number', 'Email', 'Profile Picture']);

        foreach ($users as $user) {
            $csv->insertOne([$user->id, $user->name, $user->date_of_birth, $user->age, $user->phone_number, $user->email, $user->profile_picture]);
        }

        $this->info("{$filename} created successfully.");
    }
}
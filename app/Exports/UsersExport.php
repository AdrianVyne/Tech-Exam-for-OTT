<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\User;


class UsersExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    public function collection()
    {
        return User::select('profile_picture', 'id', 'name', 'date_of_birth', 'age', 'phone_number', 'email')
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Picture',
            'ID',
            'Name',
            'DOB',
            'Age',
            'Phone',
            'Email',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'jpeg',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $users = User::all();

                foreach ($users as $key => $user) {
                    if ($user->profile_picture) {
                        $drawing = new Drawing();
                        $drawing->setName('Profile Picture');
                        $drawing->setDescription('Profile Picture');
                        $drawing->setPath(public_path('profile_pictures/' . basename($user->profile_picture)));
                        $drawing->setHeight(100);
                        $drawing->setWidth(100);
                        $drawing->setCoordinates('A' . ($key + 2));
                        $drawing->setWorksheet($event->sheet->getDelegate());
                    }

                }
            },
        ];
    }
}
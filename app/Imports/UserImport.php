<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{

    protected $id_category;

    public function __construct(string $id_category)
    {
        $this->id_category = $id_category;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (empty($row['nama']) || empty($row['email'])) {
            return null; // skip baris kosong
        }

        return new User([
            'name'     => $row['nama'],
            'email'    => $row['email'],
            'password' => Hash::make($row['password']),
            'id_category' => $this->id_category,
            'phone'  => $row['nomor_handphone'],
            'address'   => $row['alamat'],
        ]);
    }

    public function collection(Collection $rows)
    {
        $errors = [];
        $newUsers = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // karena heading di row 1

            // Skip baris kosong total
            if (
                empty($row['nama']) &&
                empty($row['email']) &&
                empty($row['password']) &&
                empty($row['nomor_handphone']) &&
                empty($row['alamat'])
            ) {
                continue;
            }

            // Cek email atau phone sudah ada di DB?
            $phone = preg_replace('/[^\d]+/', '', (string) $row['nomor_handphone']);
            $emailExists = User::where('email', $row['email'])->exists();
            $phoneExists = User::where('phone', $phone)->exists();

            if ($emailExists || $phoneExists) {
                $errors[] = "Baris $rowNum: " .
                    ($emailExists ? "Email sudah terpakai. " : "") .
                    ($phoneExists ? "Nomor HP sudah terpakai." : "");
                continue;
            }

            $newUsers[] = [
                'name' => $row['nama'],
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
                'id_category' => $this->id_category,
                'phone' => $phone,
                'address' => $row['alamat'],
            ];
        }

        // Kalau ada error, lempar ke controller
        if (!empty($errors)) {
            throw new \Exception(join("\n", $errors));
        }

        // Import semua
        User::insert($newUsers);
    }
}

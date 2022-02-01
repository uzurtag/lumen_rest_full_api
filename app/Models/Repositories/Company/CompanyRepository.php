<?php

namespace App\Models\Repositories\Company;

use Illuminate\Support\Facades\DB;

class CompanyRepository
{
    public function show(array $data) : array
    {
        $result = DB::select("SELECT * FROM companies WHERE user_id = :id", $data);

        return $result;
    }

    public function store(array $data) : bool
    {
        $result = DB::insert
        ("INSERT INTO companies (user_id, title, description, phone) VALUES (?, ?, ?, ?)", $data);

        return $result;
    }
}

<?php

namespace App\Models\Repositories\User;

use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * @param array $data
     * @return array
     */
    public function find(array $data) : array
    {
        $result = DB::select("SELECT * FROM users WHERE email = :email", $data);

        return $result;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function store(array $data) : bool
    {
        $result = DB::insert(
            "INSERT INTO users (email, password, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?)",
            $data
        );

        return $result;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function restore(array $data) : bool
    {
        $result = DB::update("UPDATE users SET password = :password WHERE email = :email", $data);

        return $result;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateToken(array $data) : bool
    {
        $result = DB::update(
            "UPDATE users SET token = :token WHERE email = :email", $data);

        return $result;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateHash(array $data) : bool
    {
        $result = DB::update("UPDATE users SET password_hash = :password_hash WHERE email = :email", $data);

        return $result;
    }
}

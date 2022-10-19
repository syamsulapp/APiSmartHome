<?php

namespace App\Repositories;

use App\Http\JsonBuilder\ReturnResponse;

class BaseRepository extends ReturnResponse
{

    // logic custom error
    public function customError($validator)
    {
        $errors = collect($validator);
        $result = collect([]);

        foreach ($errors as $key => $value) {

            $custom = [
                'message' => $value[0],
                'field' => $key,
            ];
            $result->push($custom);
        }
        $data = array('errors' => $result);
        return $this->error422($data, 'Data tidak lengkap');
    }
}

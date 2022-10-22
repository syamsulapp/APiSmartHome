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

    public function responseCode($data, String $message = '', $code = 200)
    {
        switch ($code) {
            case 200:
                $result = $this->successOk($data, $message);
                break;
            case 422:
                $result = $this->error422($data, $message);
                break;
            case 426:
                $result = $this->error426($data, $message);
                break;
            case 401:
                $result = $this->error401($data, $message);
                break;
        }
        return $result;
    }
}

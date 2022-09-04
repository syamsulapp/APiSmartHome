<?php

namespace App\Http\JsonBuilder;

class ReturnResponse
{
	public static function responData($data, int $statusCode = 200, String $message = 'success data')
	{
		switch ($statusCode) {
			case 200:
				$respon = [
					'statusCode' => $statusCode,
					'message' => $message,
					'data' => $data,
				];
			default:
				$respon = [
					'statusCode' => $statusCode,
					'message' => $message,
					'data' => $data,
				];

				return response()->json($respon, $statusCode);
		}
	}
}

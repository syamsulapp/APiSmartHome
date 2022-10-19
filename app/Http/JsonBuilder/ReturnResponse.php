<?php

namespace App\Http\JsonBuilder;

class ReturnResponse
{
	public static function successOk($data, String  $message = 'SuccessFully Data', int  $statusCode = 200)
	{
		switch ($statusCode) {
			case 200:
				$respon = [
					'message' => $message,
					'data' => $data,
				];
				break;
			default:
				$respon = array('message' => 'status code ini bukan untuk 200');
				break;
		}
		return response()->json($respon, $statusCode);
	}

	public static function error422($data, String $message = 'Failed Request', int $statusCode = 422)
	{
		switch ($statusCode) {
			case 422:
				$respon = [
					'message' => $message,
					'data' => $data
				];
				break;
			default:
				$respon = array('message' => 'status code bukan ini untuk 422');
				break;
		}
		return response()->json($respon, $statusCode);
	}

	public static function error426($data, String $message = 'Please Upgrade', int $statusCode = 426)
	{
		switch ($statusCode) {
			case 426:
				$respon = [
					'message' => $message,
					'data' => $data,
				];
				break;
			default:
				$respon = array('message' => 'status code ini bukan untuk 426');
				break;
		}
		return response()->json($respon, $statusCode);
	}

	public static function error500($data, String $message = 'Error Sistem', int $statusCode = 500)
	{
		switch ($statusCode) {
			case 500:
				$respon = [
					'message' => $message,
					'data' => $data,
				];
			default:
				$respon = array('message' => 'status code ini bukan untuk 500');
				break;
		}
		return response()->json($respon, $statusCode);
	}

	public static function error401($data, String $message = 'Unautorized', int $statusCode = 401)
	{
		switch ($statusCode) {
			case 401:
				$respon = [
					'Message' => $message,
					'data' => $data,
				];
				break;
			default:
				$respon = array('message' => 'status code bukan 401');
				break;
		}
		return response()->json($respon, $statusCode);
	}
}

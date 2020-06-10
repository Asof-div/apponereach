<?php

namespace App\Services\Response;

class ApiResponse
{
	public function success(array $data)
	{
		$response = new \StdClass;
		$items = new \StdClass;

		foreach ($data as $name => $value) {
			$items->{$name} = $value;
		}

		$response->data = $items;

		return $response;
	}

	public function error($message)
	{
		$response = new \StdClass;
		$item = new \StdClass;
		$item->message = $message;

		$response->error = $item;

		return $response;
	}

}
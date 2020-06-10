<?php

namespace App\Services\Voucher;

class Generator
{
	public function generateVoucherCode()
	{
	    $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
	    $count = mb_strlen($chars);
	    $code = '';

		for ($i = 0; $i < 10; $i++) {
	        $index = rand(0, $count - 1);
	        $code .= mb_substr($chars, $index, 1);
	    }

	    return $code;
	}

}
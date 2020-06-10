<?php

namespace App\Services\Cart\Interface;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;

interface CartItemService{

	public function addItem(Order $order, Request $request) : Order;

	public function removeItem(Order $order, Request $request) : Order;

	public function checkoutItem(Transaction $transaction, OrderItem $item);


}

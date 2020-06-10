<?php

/**
* Deduct the call credits based on the call rate and call duration
*/

namespace App\Services\Call;

use App\Services\Location\Location;
use App\Repositories\UserRepository;
use App\Repositories\CallRepository;
use App\Repositories\CreditRepository;

use Carbon\Carbon;

class CallCreditsDeducer
{
	protected $userRepo;
	protected $callRepo;
	protected $creditRepo;

	public function __construct(UserRepository $userRepo, CallRepository $callRepo, CreditRepository $creditRepo)
	{
		$this->userRepo = $userRepo;
		$this->callRepo = $callRepo;
		$this->creditRepo = $creditRepo;
	}

	public function deduct(string $caller_no, string $callee_no, string $start_time, string $end_time)
	{
		$caller = $this->userRepo->findByPhonenumber($caller_no);
		$callee = $this->userRepo->findByPhonenumber($callee_no);

		if ($callee) {
			$call_rate = $this->callRepo->findCallRateByCountryId($callee->country_id);	
		} else {
			$country = Location::getCountryByPhonenumber($callee_no);
			$call_rate = $this->callRepo->findCallRateByCountryId($country->id);	
		}

		$call_duration_in_seconds = $this->calculateCallDurationInSeconds($start_time, $end_time);

		if ($call_duration_in_seconds < 1) return false;

		$credits_used = $this->calculateCreditsUsedFromCallDuration($call_rate, $call_duration_in_seconds);

		$this->creditRepo->debitUser($caller, $credits_used);

		return true;
	}

	private function calculateCreditsUsedFromCallDuration(float $call_rate, int $duration): float
	{
		$rate_per_secs = $call_rate / 60;

		return round($rate_per_secs * $duration, 2);
	}

	private function calculateCallDurationInSeconds(string $start_time, string $end_time): int
	{
		return Carbon::parse($start_time)->diffInSeconds(Carbon::parse($end_time), false);
	}

}
<?php

/**
* Get the maximum call duration for a call based on the call destination and the caller available credits
*/

namespace App\Services\Call;

use App\Repositories\UserRepository;
use App\Repositories\CallRepository;

use App\Services\Location\Location;

class MaxCallDurationCalculator
{
	protected $userRepo;
	protected $callRepo;
	protected $location;

	public function __construct(UserRepository $userRepo, CallRepository $callRepo)
	{
		$this->userRepo = $userRepo;
		$this->callRepo = $callRepo;
	}

	public function calculate(string $caller_no, string $callee_no)
	{
		$caller = $this->userRepo->findByPhonenumber($caller_no);

		$callee_country = Location::getCountryByPhonenumber($callee_no);

		$call_rate = $this->callRepo->findCallRateByCountryId($callee_country->id);

		if (is_null($call_rate)) return ['success' => false, 'error' => 'Callee country call rate not set'];

		$seconds = $this->calculateMaxCallDurationInSeconds($call_rate, $caller->credits);

		return ['success' => true, 'data' => $seconds];
	}

	private function calculateMaxCallDurationInSeconds($rate, $credits)
	{
		if ($credits == 0) return 0;

		$rate_per_secs = $rate / 60;

		return floor($credits / $rate_per_secs);
	}

}
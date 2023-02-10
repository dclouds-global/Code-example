<?php

namespace App\Domain\Partner\UseCase\Create;

use App\Events\Partner\PartnerCreatedEvent;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class Handler
{
    /**
     * @throws QueryException
     */
    public function execute(Action $action): int
    {
        $partner = Partner::create([
            'name' => $action->name,
            'inn' => $action->inn,
            'kpp' => $action->kpp,
            'legal_address' => $action->legal_address,
            'actual_address' => $action->actual_address,
            'bik' => $action->bik,
            'bank_name' => $action->bank_name,
            'correspondent_account' => $action->correspondent_account,
            'checking_account' => $action->checking_account,
            'api_prefix' => $action->api_prefix,
            'api_login' => $action->api_login,
            'api_password' => $action->api_password,
            'deposit' => $action->deposit,
            'commission' => $action->commission,
            'service_transfer_sum_at_a_time_max' => $action->service_transfer_sum_at_a_time_max,
            'service_transfer_sum_at_a_time_min' => $action->service_transfer_sum_at_a_time_min,
            'service_transfer_sum_max_month' => $action->service_transfer_sum_max_month,
            'service_transfer_commission' => $action->service_transfer_commission,
            'service_transfer_commission_min' => $action->service_transfer_commission_min,
        ]);

        /** @var User $currentUser */
        $currentUser = Auth::user();
        event(new PartnerCreatedEvent($currentUser, $partner));

        return $partner->id;
    }
}

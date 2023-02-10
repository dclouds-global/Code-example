<?php

namespace App\Domain\Partner\Services;

use App\Domain\Partner\DTO\PartnerListFilterDTO;
use App\Exceptions\PartnerException;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class PartnerService
{
    /**
     * @throws InvalidArgumentException
     */
    public function getPaginatedPartners(
        PartnerListFilterDTO $partnerListFilterDTO
    ): LengthAwarePaginator {
        $user = Auth::user();
        /** @var User $user */
        if ($user->isPartnerUser() === true) {
            $items = Partner::where('id', '=', $user->partner_id);
        } else {
            $items = Partner::query();
        }

        if ($partnerListFilterDTO->filterId !== null) {
            $items->where('id', $partnerListFilterDTO->filterId);
        }
        if ($partnerListFilterDTO->filterBlocked !== null) {
            $items->where('blocked', $partnerListFilterDTO->filterBlocked);
        }
        if ($partnerListFilterDTO->filterName !== null) {
            $items->where('name', 'ILIKE', '%' . $partnerListFilterDTO->filterName . '%');
        }
        if ($partnerListFilterDTO->filterInn !== null) {
            $items->where('inn', 'LIKE', '%' . $partnerListFilterDTO->filterInn . '%');
        }
        if ($partnerListFilterDTO->filterDeposit !== null) {
            $items->where('deposit', $partnerListFilterDTO->filterDeposit);
        }
        if ($partnerListFilterDTO->filterCommission !== null) {
            $items->where('commission', $partnerListFilterDTO->filterCommission);
        }
        $items->orderBy($partnerListFilterDTO->sort, $partnerListFilterDTO->sortType);

        return $items->paginate($partnerListFilterDTO->limit, page:$partnerListFilterDTO->page);
    }

    public function blockPartner(
        Partner $partner
    ): bool {
        return $partner->update(['blocked' => true]);
    }

    public function unblockPartner(
        Partner $partner
    ): bool {
        return $partner->update(['blocked' => false]);
    }

    public function addPartnerDeposit(
        Partner $partner,
        float $sum
    ): float {
        $partner->update(['deposit' => $partner->deposit + $sum]);
        return $partner->deposit;
    }

    /**
     * @throws PartnerException
     */
    public function subPartnerDeposit(
        Partner $partner,
        float $sum
    ): float {
        if ($partner->deposit < $sum) {
            throw new PartnerException('На депозите недостаточно средств', 403);
        }
        $partner->update(['deposit' => $partner->deposit - $sum]);
        return $partner->deposit;
    }

    public function getPartnerDeposit(
        Partner $partner
    ): float {
        return $partner->deposit;
    }

    public function getRealPartnerDeposit(
        Partner $partner
    ): float {
        return $partner->deposit - $partner->holded_amount;
    }


    public function addPartnerCommission(
        Partner $partner,
        float $sum
    ): float {
        $partner->update(['commission' => $partner->commission + $sum]);
        return $partner->commission;
    }

    /**
     * @throws PartnerException
     */
    public function subCommission(
        Partner $partner,
        float $sum
    ): float {
        if ($partner->commission < $sum) {
            throw new PartnerException('Неоплаченная комиссия не может быть меньше 0', 403);
        }
        $partner->update(['commission' => $partner->commission - $sum]);
        return $partner->commission;
    }

    /**
     * @throws PartnerException
     */
    public function getParameters(): Partner
    {
        if (Auth::user()->partner === null) {
            throw new PartnerException('У пользователя нет партнера. Обратитесь к администратору', 403);
        }
        return Auth::user()->partner;
    }
}

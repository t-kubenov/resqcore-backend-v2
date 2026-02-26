<?php

namespace App\FieldOption\Support;

class FieldOptionContext
{
    public function __construct(
        public readonly ?int $companyId,
        public readonly ?int $branchId,
        public readonly ?int $legalEntityId,
    ){}

    public static function fromAuth(): self
    {
        $user = auth()->user();

        return new self(
            companyId: $user->rescue_company_id,
            branchId: $user->branch_id,
            legalEntityId: request()->input('legal_entity_id'),
        );
    }
}

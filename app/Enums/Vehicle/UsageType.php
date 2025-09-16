<?php

namespace App\Enums\Vehicle;

enum UsageType: string
{
    case Rental = 'rental'; // Аренда
    case CustomerRental = 'customer_rental'; // Аренда у заказчика
    case Owned = 'owned'; // Собственность
    case CustomerOwned = 'customer_owned'; // Собственность заказчика
    case TransferredOwnership = 'transferred_ownership'; // Собственность передан
}

<?php

namespace App\Vehicle\Enums;

enum UsageType: int
{
    case Owned = 0; // Собственность
    case Rental = 1; // Аренда
    case CustomerRental = 2; // Аренда у заказчика
    case CustomerOwned = 3; // Собственность заказчика
    case TransferredOwnership = 4; // Собственность передан
}

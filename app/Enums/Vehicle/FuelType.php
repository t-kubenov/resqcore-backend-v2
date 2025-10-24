<?php

namespace App\Enums\Vehicle;

enum FuelType: int
{
    case Diesel = 0; // дизель
    case Gasoline = 1; // бензин
    case EV = 2; // электродвигатель
}

<?php

namespace App\Enums\Vehicle;

enum DriveType: int
{
    case RearWheelDrive = 0;
    case FrontWheelDrive = 1;
    case AllWheelDrive = 2;
    case FourWheelDrive = 3;
}

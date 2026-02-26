<?php

namespace App\Vehicle\Enums;

enum DriveType: int
{
    case RearWheelDrive = 0;
    case FrontWheelDrive = 1;
    case AllWheelDrive = 2;
    case FourWheelDrive = 3;
}

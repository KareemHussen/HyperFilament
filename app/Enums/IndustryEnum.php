<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum IndustryEnum : Int implements HasLabel
{
    case Agriculture = 1;
    case Fishing = 2;
    case Forestry = 3;
    case Mining = 4;
    case OilAndGas = 5;

    case Construction = 6;
    case Manufacturing = 7;
    case FoodAndBeverage = 8;
    case TextilesAndApparel = 9;
    case Chemicals = 10;
    case Pharmaceuticals = 11;
    case MetalsAndSteel = 12;
    case Automotive = 13;
    case Electronics = 14;
    case RenewableEnergy = 15;

    case InformationTechnology = 16;
    case Software = 17;
    case Telecommunications = 18;
    case FinancialServices = 19;
    case Banking = 20;
    case Insurance = 21;
    case RealEstate = 22;
    case TransportationAndLogistics = 23;
    case TourismAndHospitality = 24;
    case Healthcare = 25;
    case Education = 26;
    case MediaAndEntertainment = 27;
    case MarketingAndAdvertising = 28;
    case Consulting = 29;
    case Retail = 30;
    case ECommerce = 31;

    case ArtificialIntelligence = 32;
    case FinTech = 33;
    case Cybersecurity = 34;
    case CleanEnergy = 35;
    case Printing3D = 36;
    case DataScience = 37;
    case Aerospace = 38;
    case VirtualReality = 39;
    case Robotics = 40;
    case Biotechnology = 41;

    case Other = 99;

    public function getLabel(): ?string
    {
        return $this->name;
        
    }
}

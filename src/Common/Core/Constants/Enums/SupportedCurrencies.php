<?php

namespace Fundraiser\Common\Core\Constants\Enums;

enum SupportedCurrencies: string
{
    case Euro = 'EUR';
    case USDollar = 'USD';
    case PoundSterling = 'GBP';
}

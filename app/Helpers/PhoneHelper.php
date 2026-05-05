<?php

namespace App\Helpers;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

class PhoneHelper
{
    public static function formatPhoneNumber($phoneNumber, $region = 'VN'): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $numberProto = $phoneUtil->parse($phoneNumber, $region);
            return $phoneUtil->format($numberProto, PhoneNumberFormat::INTERNATIONAL);
        } catch (NumberParseException $e) {
            return "Số điện thoại không hợp lệ: " . $e->getMessage();
        }
    }

    public static function isValidPhoneNumber($phoneNumber, $region = 'VN'): bool
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $numberProto = $phoneUtil->parse($phoneNumber, $region);
            return $phoneUtil->isValidNumber($numberProto);
        } catch (NumberParseException $e) {
            return false;
        }
    }
}

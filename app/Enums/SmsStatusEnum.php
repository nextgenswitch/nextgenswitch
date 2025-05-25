<?php
namespace App\Enums;

enum SmsStatusEnum: Int {

case Queued    = 0;
case Sent      = 1;
case Delivered = 2;
case Failed    = 3;

    public function getText(): string {
        return match ( $this ) {
            SmsStatusEnum::Queued => __( "Queued" ),
            SmsStatusEnum::Sent => __( "Sent" ),
            SmsStatusEnum::Delivered => __( "Delivered" ),
            SmsStatusEnum::Failed => __( "Failed" ),
        };

    }

    public static function statuses(): array {
        $array = [];

        foreach ( self::cases() as $case ) {
            $array[$case->value] = $case->getText();
        }

        return $array;
    }

}

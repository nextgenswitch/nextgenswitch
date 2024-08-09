<?php
namespace App\Enums;

enum QueueStatusEnum: Int {

case Queued      = -1;
case Dialing     = 0;
case Bridging    = 1;
case Established = 2;
case Bridged     = 3;
case Error       = 4;
case Hangup      = 5;
case Leave       = 6;
case QueueFull   = 7;

    public function getText(): string {
        return match ( $this ) {
            QueueStatusEnum::Queued => __( "Queued" ),
            QueueStatusEnum::Dialing => __( "Dialing" ),
            QueueStatusEnum::Bridging => __( "Bridging" ),
            QueueStatusEnum::Established => __( "Established" ),
            QueueStatusEnum::Bridged => __( "Bridged" ),
            QueueStatusEnum::Error => __( "Error" ),
            QueueStatusEnum::Hangup => __( "Hangup" ),
            QueueStatusEnum::Leave => __( "Leave" ),
            QueueStatusEnum::QueueFull => __( "QueueFull" ),
        };

    }

    public static function statuses(): array {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->getText();
        }
        return $array;
    }

}

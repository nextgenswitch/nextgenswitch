<?php
namespace App\Enums;

enum CallStatusEnum: Int {

case Queued       = -1;
case Dialing      = 0;
case Ringing      = 1;
case Established  = 2;
case Disconnected = 3;
case Busy         = 4;
case NoAnswer     = 5;
case Cancelled    = 6;
case Failed       = 7;

    public function getText(): string {
        return match ( $this ) {
            CallStatusEnum::Queued => __( "Queued" ),
            CallStatusEnum::Dialing => __( "Dialing" ),
            CallStatusEnum::Ringing => __( "Ringing" ),
            CallStatusEnum::Established => __( "Established" ),
            CallStatusEnum::Disconnected => __( "Disconnected" ),
            CallStatusEnum::Busy => __( "Busy" ),
            CallStatusEnum::NoAnswer => __( "NoAnswer" ),
            CallStatusEnum::Cancelled => __( "Cancelled" ),
            CallStatusEnum::Failed => __( "Failed" ),
        };

    }

    public static function activeCallStatuses(): array {

        return [
            '0' => self::Dialing->getText(),
            '1' => self::Ringing->getText(),
            '2' => self::Established->getText(),
        ];

    }

    public static function callLogStatuses(): array {

        return [
            '3' => self::Disconnected->getText(),
            '4' => self::Busy->getText(),
            '5' => self::NoAnswer->getText(),
            '6' => self::Cancelled->getText(),
            '7' => self::Failed->getText(),
        ];

    }

    public static function fromKey(int $key): ?self {
        return match ($key) {
            -1 => self::Queued,
            0  => self::Dialing,
            1  => self::Ringing,
            2  => self::Established,
            3  => self::Disconnected,
            4  => self::Busy,
            5  => self::NoAnswer,
            6  => self::Cancelled,
            7  => self::Failed,
            default => null,
        };
    }

}

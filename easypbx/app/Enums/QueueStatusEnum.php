<?php
namespace App\Enums;

enum QueueStatusEnum: Int {

case Queued     = 0;
case Bridging    = 1;
case Bridged = 2;
case Disconnected  = 3;
case Abandoned       = 4;
case Timeout      = 5;

    public function getText(): string {
        return match ( $this ) {
            QueueStatusEnum::Queued => __( "Queued" ),
            QueueStatusEnum::Bridging => __( "Bridging" ),
            QueueStatusEnum::Bridged => __( "Bridged" ),
            QueueStatusEnum::Disconnected => __( "Disconnected" ),
            QueueStatusEnum::Abandoned => __( "Abandoned" ),
            QueueStatusEnum::Timeout => __( "Timeout" ),
        };

    }

    public function getCss(): string {
        return match ( $this ) {
            QueueStatusEnum::Queued => __( "text-info" ),
            QueueStatusEnum::Bridging => __( "text-info" ),
            QueueStatusEnum::Bridged => __( "text-info" ),
            QueueStatusEnum::Disconnected => __( "text-success" ),
            QueueStatusEnum::Abandoned => __( "text-warning" ),
            QueueStatusEnum::Timeout => __( "text-danger" ),
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

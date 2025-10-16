<?php

namespace App\Models;

use App\Models\Call;
use App\Models\Lead;
use App\Models\Ticket;
use Illuminate\Support\Str;
use App\Enums\CallStatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Contact extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'cc',
        'tel_no',
        'contact_groups',
        'address',
        'city',
        'state',
        'post_code',
        'country',
        'notes'
    ];

    public static function customerInfo($contact) {}

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] =  $value ? $value : 'Unnamed';
    }

    protected function telNo(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => self::sanitize_phone($value),
            set: fn(string $value) => self::sanitize_phone($value),
        );
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function getContactGroupsAttribute($value)
    {
        return explode(',', $value);
    }

    public function setContactGroupsAttribute($value)
    {
        $this->attributes['contact_groups'] =  ! empty($value) ? implode(',', $value) : null;
    }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return \DateTime::createFromFormat('j/n/Y g:i A', $value);
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return \DateTime::createFromFormat('j/n/Y g:i A', $value);
    }

    public static function  sanitize_phone($phone)
    {

        $intl = false;
        if (strlen($phone) == 0)
            return $phone;
        $phone = trim($phone);
        if (substr($phone, 0, 1) == '+') {
            $intl = true;
            $phone = substr($phone, 1);
        }

        $phone = preg_replace('/\D+/', '', $phone);

        if ($intl) $phone = '+' . $phone;

        return $phone;
    }

    //Contact::getContacts([3,4])->count()
    public static function  getContacts($groups): array | object
    {
        //$contacts = Contact::select( [DB::raw( "CONCAT(COALESCE(cc, ''),tel_no) as tel" )] );
        $contacts = Contact::query();

        foreach ($groups as $key => $groupId) {
            $statement = $key === 0 ? 'whereRaw' : 'orWhereRaw';
            $contacts->{$statement}('FIND_IN_SET(?, contact_groups)', [$groupId]);
        }

        /*  foreach ($groups as $groupId) {
            $contacts->orWhere(function ($query) use ($groupId) {
                $query->where('contact_groups', $groupId)
                    ->orWhere('contact_groups', 'like', $groupId . ',%')
                    ->orWhere('contact_groups', 'like', '%,' . $groupId)
                    ->orWhere('contact_groups', 'like', '%,' . $groupId . ',%');
            });
        }
 */

        return  $contacts
            ->groupBy('tel_no')
            ->pluck('tel_no', 'id');
    }

    private static function normalizePhone(string $number): string
    {
        return preg_replace('/[^\d+]/', '', $number) ?? $number;
    }

    private static function initialsFromName(?string $name): ?string
    {
        $name = trim((string) $name);
        if ($name === '') return null;

        $parts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);
        if (!$parts || count($parts) === 0) return null;

        if (count($parts) === 1) {
            return Str::upper(mb_substr($parts[0], 0, 2));
        }
        return Str::upper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
    }

    private static function displayNameForNumber(int|string $orgId, ?string $phone): ?string
    {
        static $memo = [];
        $phone = self::normalizePhone((string) $phone);

        if ($phone === '') return null;
        if (isset($memo[$phone])) return $memo[$phone];

        $lead = Lead::where('organization_id', $orgId)->where('phone', $phone)->first();
        if ($lead && !empty($lead->name)) {
            return $memo[$phone] = trim($lead->name) . " ({$phone})";
        }

        $contact = Contact::where('organization_id', $orgId)->where('tel_no', $phone)->first();
        if ($contact) {
            $full = trim(($contact->first_name ?? '') . ' ' . ($contact->last_name ?? ''));
            if ($full !== '') {
                return $memo[$phone] = "{$full} ({$phone})";
            }
        }

        return $memo[$phone] = $phone;
    }

    /**
     * Accept enum|int|string|null and return CallStatusEnum|null.
     */
    private static function normalizeCallStatus($raw): ?CallStatusEnum
    {
        if ($raw instanceof CallStatusEnum) return $raw;
        if (is_numeric($raw)) return CallStatusEnum::fromKey((int) $raw);
        if (is_string($raw) && is_numeric($raw)) return CallStatusEnum::fromKey((int) $raw); // kept for parity
        return null;
    }

    /**
     * Human qualifier for the note based on status + duration.
     */
    private static function qualifierFromStatus(?CallStatusEnum $status, $rawDuration): ?string
    {
        $dur = is_numeric($rawDuration) ? (int) $rawDuration : null;

        return match ($status) {
            CallStatusEnum::Busy,
            CallStatusEnum::NoAnswer,
            CallStatusEnum::Cancelled,
            CallStatusEnum::Failed        => 'Missed',
            CallStatusEnum::Disconnected  => ($dur !== null && $dur > 0) ? 'Answered' : 'Missed',
            CallStatusEnum::Dialing,
            CallStatusEnum::Ringing       => 'Ringing',
            CallStatusEnum::Established   => ($dur !== null && $dur > 0) ? 'Answered' : null,
            CallStatusEnum::Queued        => 'Queued',
            default                       => ($dur !== null && $dur > 0) ? 'Answered' : null,
        };
    }


    public static function customerLookup(string $rawNumber, $organizationId = null)
    {
        $orgId  = $organizationId ?? auth()->user()->organization_id;
        $number = self::normalizePhone($rawNumber);

        // Tickets (unchanged logic; minor tidy)
        $ticketQuery = Ticket::where('organization_id', $orgId)
            ->where('phone', $number)
            ->where('status', 4);

        $ticketCount = (clone $ticketQuery)->count();
        $tickets     = (clone $ticketQuery)->latest('created_at')->take(5)->get();

        // Base response
        $response = [
            'name'        => 'Unknown',
            'phone'       => $number,
            'email'       => '-',
            'company'     => '-',
            'designation' => '-',
            'tags'        => [],
            'tickets'     => $ticketCount,
            'avatar'      => 'UK',
        ];

        // Prefer Lead; else Contact (unchanged behavior)
        $lead = Lead::where('organization_id', $orgId)
            ->where('phone', $number)
            ->first();

        if ($lead) {
            $response['name']        = $lead->name ?: 'Unknown';
            $response['phone']       = $lead->phone ?: $number;
            $response['email']       = $lead->email ?: '-';
            $response['company']     = $lead->company ?: '-';
            $response['designation'] = $lead->designation ?: '-';
            $response['avatar']      = self::initialsFromName($lead->name) ?? 'UK';
        } else {
            $contact = self::where('organization_id', $orgId)
                ->where('tel_no', $number)
                ->first();

            if ($contact) {
                $fullName = trim(($contact->first_name ?? '') . ' ' . ($contact->last_name ?? ''));
                $response['name']   = $fullName !== '' ? $fullName : 'Unknown';
                $response['phone']  = $contact->tel_no ?: $number;
                $response['email']  = $contact->email ?: '-';
                $response['avatar'] = self::initialsFromName($fullName) ?? 'UK';
            }
        }

        // Calls for timeline (eager-load bridgeCall since we read it)
        $calls = self::callListQuery($orgId, $number, null, 10)
            ->with('bridgeCall')
            ->get();

        $timeline = collect();

        foreach ($calls as $c) {
            $isIncoming   = (int) ($c->uas ?? 0) === 1;
            $fromNumber   = self::normalizePhone((string) optional($c)->caller_id);
            $toNumber     = self::normalizePhone((string) optional($c)->destination);
            $statusText   = $c->status->getText();
            $rawDuration  = $c->duration;
            $durationStr  = function_exists('duration_format') ? duration_format($rawDuration) : (string) $rawDuration;

            // Counterparty + display name (same logic as before)
            $counterparty = $isIncoming ? ($fromNumber ?: $toNumber) : ($toNumber ?: $fromNumber);
            $who          = self::displayNameForNumber($orgId, $counterparty) ?? ($counterparty ?: 'Unknown');

            $dirText     = $isIncoming ? 'Incoming' : 'Outgoing';
            $statusEnum  = self::normalizeCallStatus(optional($c->bridgeCall)->status ?? $c->status ?? null);
            $qualifier   = self::qualifierFromStatus($statusEnum, $rawDuration);

            $noteParts = [
                "{$dirText} call",
                // $isIncoming ? 'from' : 'to',
                // $who,
            ];
            if ($qualifier) {
                $noteParts[] = "- {$qualifier}";
            }
            // if ($statusText !== '-') {
            //     $noteParts[] = "({$statusText})";
            // }
            if (!empty($durationStr)) {
                $noteParts[] = "- {$durationStr}";
            }

            $timeline->push([
                'type'      => 'call',
                'direction' => $dirText,
                'qualifier' => $qualifier,
                'ts'        => $c->created_at,
                'when'      => null, // fill after sorting
                'note'      => implode(' ', $noteParts),
                'status'    => $statusText,
                'duration'  => $durationStr ?: '-',
            ]);
        }

        // Ticket items (unchanged)
        foreach ($tickets as $t) {
            $subject = trim((string) ($t->subject ?? 'Ticket'));
            $timeline->push([
                'type' => 'ticket',
                'ts'   => $t->created_at,
                'when' => null,
                'note' => "{$subject} - Opened",
            ]);
        }

        // Sort desc, format 'when', keep top 5 (unchanged)
        $timeline = $timeline
            ->sortByDesc('ts')
            ->values()
            ->take(5)
            ->map(function ($item) {
                $item['when'] = $item['ts']->diffForHumans();
                unset($item['ts']);
                return $item;
            })
            ->all();

        return array_merge($response, ['timeline' => $timeline]);
    }

    private static function callListQuery(int|string $orgId, string $number, ?string $search, int $limit)
    {
        $search = $search !== null ? trim($search) : null;

        return Call::where('organization_id', $orgId)
            ->where(function ($query) use ($number, $search) {
                $query->where(function ($q) use ($number, $search) {
                    $q->where('caller_id', $number)
                        ->where('uas', 1);

                    if ($search !== null && $search !== '') {
                        $q->where('destination', 'like', "%{$search}%");
                    }
                })
                ->orWhere(function ($q) use ($number, $search) {
                    $q->where('destination', $number)
                        ->where('uas', 0);

                    if ($search !== null && $search !== '') {
                        $q->where('caller_id', 'like', "%{$search}%");
                    }
                });
            })
            ->where('status', '>=', CallStatusEnum::Disconnected->value)
            ->latest('created_at')
            ->limit($limit);
    }

}

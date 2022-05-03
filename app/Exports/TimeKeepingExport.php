<?php

namespace App\Exports;

use App\Models\ConfigTimeKeeping;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimeKeepingExport implements WithMapping, WithHeadings, FromQuery
{
    private array $labels;

    private array $timeKeeping;

    private array $filters;

    public function __construct(array $data = [])
    {
        $this->filters = $data?? [];
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        $res = [
            '#',
            'Tên nhân viên',
        ];

        foreach ($this->labels as $val) {
            $res[] = $val;
        }

        return $res;
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $res = [];
        foreach ($this->timeKeeping as $key => $value) {
            $tmp = [];
            $tmp[] = $key+1;
            $tmp[] = $value->fullname;
            foreach ($value->time_keeping as $val) {
                $t = '';
                if (isset($val['checkin'])) {
                    $t .= $val['checkin'];
                }
                if (isset($val['checkout'])) {
                    $t .= ' - '. $val['checkout'];
                }

                $tmp[] = $t;
            }
            $res[] = $tmp;
        }

        return $res;
    }

    public function query()
    {
        $start_date = '';
        $end_date = '';
        $filters = $this->filters;
        switch ($filters['option']) {
            case 1:
                $start_date = date('Y-m-d',strtotime('monday this week'));
                $end_date = date('Y-m-d',strtotime('sunday this week +1 days'));
                break;
            case 2:
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-t'))));
                break;
        }
        $labels = [];

        $range = $this->getLabelTimeKeeping($start_date, $end_date, $labels);

        $this->labels = $labels;

        $users = \App\Models\User::getAllUser($filters, $range);

        $config = ConfigTimeKeeping::query()->where('code', '=', 'TIME')->first();

        if ($config && $config->settings) {
            $settings = json_decode($config->settings, true);
        }

        $result = [];

        foreach ($users as $user) {
            if ($user) {
                $tmp = [];
                foreach ($range as $key => $day) {
                    $tmp[$key] = [];
                    $tmp[$key]['day'] = $key;
                    if ($user->timeKeeping) {
                        foreach ($user->timeKeeping as $time) {
                            if ($time->check_date == $key) {
                                $tmp[$key]['checkin'] = $time->checkin;
                                $tmp[$key]['checkout'] = $time->checkout;

                                $configTimeKeepingDay = $settings[$day]?? [];

                                if ($configTimeKeepingDay && $configTimeKeepingDay['start_time'] && $configTimeKeepingDay['end_time']) {
                                    $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                    $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';

                                    $start = $configTimeKeepingDay['start_time'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_time']): '';
                                    $end = $configTimeKeepingDay['end_time'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_time']): '';

                                    if (($checkIn && !$checkOut) || (!$checkIn && $checkOut)) {
                                        $tmp[$key]['class'] = 'table-danger';
                                    } elseif ($checkIn && $checkOut && $checkIn <= $start && $checkOut >= $end) {
                                        $tmp[$key]['class'] = 'table-success';
                                    } elseif (($checkIn && $checkIn > $start) || ($checkOut && $checkOut < $end)) {
                                        $tmp[$key]['class'] = 'table-warning';
                                    }
                                }
                            }
                        }
                    }
                }
                $result[] = [
                    'fullname' => $user->fullname,
                    'id' => $user->id,
                    'time_keeping' => $tmp
                ];
            }

        }

        $this->timeKeeping = $result;
    }

    private function getLabelTimeKeeping(string $start_date, string $end_date, array &$labels)
    {

        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );

        foreach ($period as $key => $value) {
            $day = $value->format('Y-m-d');

            $map = [
                'monday' => 'T2',
                'tuesday' => 'T3',
                'wednesday' => 'T4',
                'thursday' => 'T5',
                'friday' => 'T6',
                'saturday' => 'T7',
                'sunday' => 'CN',
            ];

            $daySymbol = $map[lcfirst(date('l', strtotime($day)))];

            $dateRange[$day] = lcfirst(date('l', strtotime($day)));

            $labels[] = date('d/m', strtotime($day)) . '  '. $daySymbol;


        }

        return $dateRange;
    }
}

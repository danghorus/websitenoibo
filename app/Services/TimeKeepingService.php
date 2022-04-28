<?php

namespace App\Services;

use DateInterval;
use DatePeriod;
use DateTime;

class TimeKeepingService
{
    public function getAllTimeKeeping(array $filters = []) {
        $start_date = '';
        $end_date = '';
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

        $labels = $this->getLabelTimeKeeping($start_date, $end_date);

        return [
            'code' => 200,
            'labels' => $labels,
        ];

    }

    /**
     * @throws \Exception
     */
    private function getLabelTimeKeeping(string $start_date, string $end_date)
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

            $labels[] = date('d/m', strtotime($day)) . '  '. $daySymbol;


        }

        return $labels;
    }
}

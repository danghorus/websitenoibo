<?php

namespace App\Exports;

use App\Models\ConfigTimeKeeping;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimeKeepingExport implements WithHeadings, FromArray
{
    private array $labels;

    private array $timeKeeping;

    public function __construct(array $data = [])
    {
        $this->timeKeeping = $data['data']?? [];
        $this->labels = $data['labels']?? [];
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        $res = [
            'Mã nhân viên',
            'Tên nhân viên',
        ];

        foreach ($this->labels as $val) {
            $res[] = $val;
        }

        return $res;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $res = [];

        foreach ($this->timeKeeping as $value) {
            $tmp = [];
            $tmp[] = $value['id'] ?? 0;
            $tmp[] = $value['fullname'] ?? '';
            foreach ($value['time_keeping'] as $val) {
                $timeCheckin = "";
                $timeCheckin .= "Giờ vào ca";
                $timeCheckin .= $val['checkin'] ?? '--:--:--';
                $timeCheckin .= '</br>';
                $timeCheckin .= "Giờ ra ca";
                $timeCheckin .= $val['checkout'] ?? '--:--:--';
                $tmp[] = $timeCheckin;
            }

            $res[] = $tmp;
        }

        return $res;
    }
}

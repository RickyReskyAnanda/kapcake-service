<?php

namespace App\Imports;

use App\Excel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExcelImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $dt = [];
        foreach ($rows as $key => $row) 
        {
            $data = explode(';',$row);
            array_push($dt,$data);
        }

        foreach ($dt as $key => $row)
        {
            if($key > 0){
                Excel::create([
                    'date' => date('Y-m-d',strtotime(substr($row[0],2).'-'.$row[1].'-'.preg_replace('/[^0-9]/', '', $row[2]))),
                    'regional' => $row[3],
                    'branch' => $row[4],
                    'cluster' => $row[5],
                    'kabupaten' => $row[6],
                    'telkomsel' => str_replace('","', '.', $row[7]),
                    'indosat' => str_replace('","', '.', $row[8]),
                    'tri' => str_replace('","', '.', $row[9]),
                    'xl' => str_replace('","', '.', $row[10]),
                    'smartfren' => str_replace('","', '.', $row[11])
                ]);
            }
        }
    }
}

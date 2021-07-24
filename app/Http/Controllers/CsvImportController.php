<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\CsvData;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CsvImportController extends Controller
{

    public function getImport()
    {
        return view('import');
    }

    public function parseImport(CsvImportRequest $request)
    {

        $path = $request->file('csv_file')->getRealPath();

        $tempData = array_map('str_getcsv', file($path));
        $data = [];
        if (count($tempData) > 1) {
            $csv_header_fields = [];
            foreach ($tempData[0] as $key => $value) {
                $csv_header_fields[] = $value;
            }
            
            foreach (array_slice($tempData, 1) as $row) {
                $rowData = array();
                foreach ($csv_header_fields as $key=>$header) {
                    $rowData[$header] = $row[$key];
                }
                $data[]=$rowData;
            }
            $csv_data = array_slice($data, 0, 3);

            $csv_data_file = CsvData::create([
                'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
                'csv_header' => $request->has('header'),
                'csv_data' => json_encode($data)
            ]);
        } else {
            return redirect()->back();
        }
        return view('import_fields', compact( 'csv_header_fields', 'csv_data', 'csv_data_file'));

    }

    public function processImport(Request $request)
    {
        $data = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);
        foreach ($csv_data as $row) {
            $contact = new Data();
            foreach ($request->fields as $csvKey => $dbKey) {
                $contact->$dbKey = $row[$csvKey];
            }
            $contact->save();
        }

        return view('import_success');
    }

}

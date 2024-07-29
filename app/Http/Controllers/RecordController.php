<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RecordController extends Controller
{
    public function index()
{
    $authUser = auth()->user();
    if (!in_array($authUser->roles, ['admin', 'staff'])) {
        abort(403, 'Unauthorized');
    }

    $recordsData = Record::all()->map(function ($record) {
        return [
            'address' => $record->address,
            'cellphone' => $record->cellphone,
            'household_number' => $record->householdNumber,
            'housing_type' => $record->housingType,
            'housing_type2' => $record->housingType2,
            'kuryente' => $record->kuryente,
            'tubig' => $record->tubig,
            'palikuran' => $record->palikuran,
            'date_recorded' => $record->created_at->format('Y-m-d H:i:s'),
            'table_data' => $record->table_data,
            'Action' => '<div id="rowz' . $record->id . '" class="d-flex">'
                . '<a href="' . route('record.show', $record->id) . '" class="mx-1 btn btn-sm btn-primary"><i class="fa-regular fa-eye"></i> View</a>'
                . '<a href="' . route('record.edit', $record->id) . '" class="mx-1 btn btn-sm btn-warning"><i class="fa-regular fa-pen-to-square"></i> Edit</a>'
                . '<a onclick="deleteRecord(' .$record->id . ');" class="mx-1 btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></a>'
                . '<a onclick="exportToExcel(' .$record->id . ');" class="mx-1 btn btn-sm btn-success"><i class="fa-solid fa-file-arrow-down"></i></a>'
                . '</div>',
        ];
    });

    return view('records.index', compact('recordsData'));
}
    public function create() {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited Create New Record page.'
        ]);
        return view('records.create');
    }
    public function store(Request $request)
    {
        $authUser = auth()->user();
        if (!in_array($authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
    
        $data = $request->validate([
            'address' => 'required|string',
            'cellphone' => 'required|string',
            'householdNumber' => 'required|string',
            'housingType' => 'required|string',
            'housingType2' => 'required|string',
            'kuryente' => 'required|string',
            'tubig' => 'required|string',
            'palikuran' => 'required|string',
            'table_data' => 'nullable|string', 
        ]);
    
        // Decode the Base64 encoded JSON data
        if (!empty($data['table_data'])) {
            $decodedTableData = base64_decode($data['table_data']);
            $jsonTableData = json_decode($decodedTableData, true);
    
            // Ensure it's valid JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->route('records.index')->with('error', 'Invalid table data format.');
            }
    
            // Re-encode the JSON data to store in the database
            $data['table_data'] = json_encode($jsonTableData);
        }
    
        $data['user_id'] = $authUser->id;
    
        try {
            Record::create($data);
    
            ActivityLog::create([
                'user_id' => $authUser->id,
                'activity' => 'Saved new Household Record.'
            ]);
    
            return redirect()->route('records.index')->with('status', 'Record created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating record: ' . $e->getMessage());
            return redirect()->route('records.index')->with('error', 'An error occurred while creating the record.');
        }
    }
    public function show($id)
{   
    $authUser = auth()->user();
    if (!in_array($authUser->roles, ['admin', 'staff'])) {
        abort(403, 'Unauthorized');
    }

    $record = Record::find($id);
    if (!$record) {
        return redirect()->route('records.index')->with('error', 'Record not found.');
    }

    $decodedTableData = json_decode($record->table_data, true);

    $data = [
        'address' => $record->address,
        'cellphone' => $record->cellphone,
        'householdNumber' => $record->householdNumber,
        'housingType' => $record->housing_type,
        'housingType2' => $record->housing_type_2,
        'kuryente' => $record->kuryente,
        'tubig' => $record->tubig,
        'palikuran' => $record->palikuran,
        'table_data' => $decodedTableData 
    ];

    ActivityLog::create([
        'user_id' => auth()->user()->id,
        'activity' => 'View a Household Record.'
    ]);

    return view('records.view-record', compact('data'));
}

public function edit($id)
{
    $authUser = auth()->user();
    if (!in_array($authUser->roles, ['admin', 'staff'])) {
        abort(403, 'Unauthorized');
    }

    $record = Record::find($id);
    if (!$record) {
        return redirect()->route('records.index')->with('error', 'Record not found.');
    }

    $decodedTableData = json_decode($record->table_data, true);

    $data = [
        'id' => $record->id,
        'address' => $record->address,
        'cellphone' => $record->cellphone,
        'householdNumber' => $record->householdNumber,
        'housingType' => $record->housingType,
        'housingType2' => $record->housingType2,
        'kuryente' => $record->kuryente,
        'tubig' => $record->tubig,
        'palikuran' => $record->palikuran,
        'table_data' => $decodedTableData
    ];

    ActivityLog::create([
        'user_id' => auth()->user()->id,
        'activity' => 'Visited Edit Household Record.'
    ]);

    return view('records.edit', compact('data'));
}

    public function update(Request $request, $id)
    {
    $validatedData = $request->validate([
        'address' => 'required|string|max:255',
        'cellphone' => 'required|string|max:15',
        'householdNumber' => 'required|string|max:10',
        'housingType' => 'required|integer|in:1,2,3',
        'housingType2' => 'required|integer|in:1,2,3,4',
        'kuryente' => 'required|integer|in:1,2,3',
        'tubig' => 'required|integer|in:1,2,3,4',
        'palikuran' => 'required|integer|in:1,2,3',
        'table_data' => 'required|json',
    ]);

    $record = Record::findOrFail($id);

    $record->update($validatedData);

    ActivityLog::create([
        'user_id' => Auth::id(),
        'activity' => 'Updated record with ID ' . $record->address,
    ]);

    return redirect()->route('records.index')->with('success', 'Record updated successfully.');
    }

    public function destroy(Request $request, $recordId)
    {
    $authUser = auth()->user();
    if (!in_array($authUser->roles, ['admin', 'staff'])) {
        abort(403, 'Unauthorized');
    }
    $record = Record::findOrFail($recordId);
    $record->forceDelete();
    ActivityLog::create([
        'user_id' => auth()->user()->id,
        'activity' => 'Deleted a Household Record.'
    ]);
    Log::info("Deleted a record | record ID: $recordId");
    return response()->json(['message' => 'Record deleted successfully'], 200);
    }
    public function exportToExcel()
    {
        $records = Record::all();
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Set the headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Address');
        $sheet->setCellValue('C1', 'Cellphone');
        $sheet->setCellValue('D1', 'Household Number');
        $sheet->setCellValue('E1', 'Housing Type');
        $sheet->setCellValue('F1', 'Housing Type 2');
        $sheet->setCellValue('G1', 'Kuryente');
        $sheet->setCellValue('H1', 'Tubig');
        $sheet->setCellValue('I1', 'Palikuran');
    
        // Define mappings
        $housingTypeMapping = [
            '1' => 'MAY ARI',
            '2' => 'SQUAT',
            '3' => 'NANGUNGUPAHAN'
        ];
    
        $housingType2Mapping = [
            '1' => 'Yari sa Semento/ Concrete',
            '2' => 'Yari sa Semento at Kahoy /Semi-Concrete',
            '3' => 'Yari sa Kahoy o Magagaan na Materyales',
            '4' => 'Yari sa Karton, Papel o Plastik/ Salvaged house'
        ];
    
        $kuryenteMapping = [
            '1' => 'May kuryente',
            '2' => 'Walang kuryente',
            '3' => 'Nakikikabit'
        ];
    
        $tubigMapping = [
            '1' => 'GRIPO(TANZA WATER DISTRICT, SUBD.WATER PROVIDER)',
            '2' => 'POSO',
            '3' => 'GRIPO DE KURYENTE/SARILING TANGKE',
            '4' => 'BALON'
        ];
    
        $palikuranMapping = [
            '1' => 'Inidoro (Water Sealed)',
            '2' => 'Balon (Antipolo type)',
            '3' => 'Walang Palikurang (No Latrine)'
        ];
    
        // Add data to the spreadsheet
        $row = 2;
        foreach ($records as $record) {
            $sheet->setCellValue('A' . $row, $record->id);
            $sheet->setCellValue('B' . $row, $record->address);
            $sheet->setCellValue('C' . $row, $record->cellphone);
            $sheet->setCellValue('D' . $row, $record->householdNumber);
            $sheet->setCellValue('E' . $row, $housingTypeMapping[$record->housingType] ?? 'Unknown');
            $sheet->setCellValue('F' . $row, $housingType2Mapping[$record->housingType2] ?? 'Unknown');
            $sheet->setCellValue('G' . $row, $kuryenteMapping[$record->kuryente] ?? 'Unknown');
            $sheet->setCellValue('H' . $row, $tubigMapping[$record->tubig] ?? 'Unknown');
            $sheet->setCellValue('I' . $row, $palikuranMapping[$record->palikuran] ?? 'Unknown');
            $row++;
        }
    
        $writer = new Xlsx($spreadsheet);
        $fileName = 'records.xlsx';
        $filePath = storage_path($fileName);
    
        $writer->save($filePath);
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Exported all Household Record as Excel File.'
        ]);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
    public function exportSpecificRecordToExcel($recordId)
    {
        $record = Record::findOrFail($recordId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Address');
        $sheet->setCellValue('C1', 'Cellphone');
        $sheet->setCellValue('D1', 'Household Number');
        $sheet->setCellValue('E1', 'Housing Type');
        $sheet->setCellValue('F1', 'Housing Type 2');
        $sheet->setCellValue('G1', 'Kuryente');
        $sheet->setCellValue('H1', 'Tubig');
        $sheet->setCellValue('I1', 'Palikuran');

        // Define mappings
        $housingTypeMapping = [
            '1' => 'MAY ARI',
            '2' => 'SQUAT',
            '3' => 'NANGUNGUPAHAN'
        ];

        $housingType2Mapping = [
            '1' => 'Yari sa Semento/ Concrete',
            '2' => 'Yari sa Semento at Kahoy /Semi-Concrete',
            '3' => 'Yari sa Kahoy o Magagaan na Materyales',
            '4' => 'Yari sa Karton, Papel o Plastik/ Salvaged house'
        ];

        $kuryenteMapping = [
            '1' => 'May kuryente',
            '2' => 'Walang kuryente',
            '3' => 'Nakikikabit'
        ];

        $tubigMapping = [
            '1' => 'GRIPO(TANZA WATER DISTRICT, SUBD.WATER PROVIDER)',
            '2' => 'POSO',
            '3' => 'GRIPO DE KURYENTE/SARILING TANGKE',
            '4' => 'BALON'
        ];

        $palikuranMapping = [
            '1' => 'Inidoro (Water Sealed)',
            '2' => 'Balon (Antipolo type)',
            '3' => 'Walang Palikurang (No Latrine)'
        ];

        // Add data to the spreadsheet
        $sheet->setCellValue('A2', $record->id);
        $sheet->setCellValue('B2', $record->address);
        $sheet->setCellValue('C2', $record->cellphone);
        $sheet->setCellValue('D2', $record->householdNumber);
        $sheet->setCellValue('E2', $housingTypeMapping[$record->housingType] ?? 'Unknown');
        $sheet->setCellValue('F2', $housingType2Mapping[$record->housingType2] ?? 'Unknown');
        $sheet->setCellValue('G2', $kuryenteMapping[$record->kuryente] ?? 'Unknown');
        $sheet->setCellValue('H2', $tubigMapping[$record->tubig] ?? 'Unknown');
        $sheet->setCellValue('I2', $palikuranMapping[$record->palikuran] ?? 'Unknown');

        $writer = new Xlsx($spreadsheet);
        $fileName = 'record_' . $record->id . '.xlsx';
        $filePath = storage_path($fileName);

        $writer->save($filePath);
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Exported record address, ' . $record->address . ' as Excel File.'
        ]);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
    

}

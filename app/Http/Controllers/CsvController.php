<?php

namespace App\Http\Controllers;
use App\Services\CsvService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CsvController extends Controller {

    // Função para verificar as diferenças entre dois ficheiros .csv
    public function checkDifference(Request $request, CsvService $csvService) {

        // Validação dos ficheiros
        $validator = Validator::make($request->all(), [
            'old_data' => 'required|file|mimes:csv,txt',
            'new_data' => 'required|file|mimes:csv,txt'
        ]);

        // SE
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Ler o content dos ficheiros
        $oldData = file_get_contents($request->file("old_data")->getRealPath());
        $newData = file_get_contents($request->file("new_data")->getRealPath());
        
        // Return das diferenças entre os ficheiros
        return response()->json(

            // Utilizar o CsvService
            data: $csvService->checkDifferences($oldData, $newData),
            status: 200
        );
    }
}
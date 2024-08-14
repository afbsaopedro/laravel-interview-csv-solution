<?php

namespace App\Services;

class CsvService
{
    CONST NEW_LINE_DELIMITER = "\n";
    CONST CSV_DELIMITER = ";";
    CONST CARRIAGE_DELIMITER = "\r";

    public function checkDifferences($oldData, $newData)
    {
        $oldCsvData = $this->getCsvData($oldData);
        $newCsvData = $this->getCsvData($newData);

        return $this->checkLines($oldCsvData, $newCsvData);
    }

    //Função para extrair os dados do CSV e retornar-los mapeados para serem checkados 
    private function getCsvData($data)
    {
        $rows = explode(self::NEW_LINE_DELIMITER, $data); // Cada linha fica um elemento no array rows
        array_shift($rows); // Retirar a primeira linha que corresponde aos headers

        $mappedRows = []; // array de objs
        
        foreach ($rows as $row) {
            $columns =  str_getcsv($row, self::CSV_DELIMITER); // Retorna um array com os campos lidos da linha
            $id = "$columns[0]-$columns[1]-$columns[2]-$columns[3]"; // Cria um identificador. Chave primaria composta
            $mappedRows[$id] = str_replace(self::CARRIAGE_DELIMITER, "", $row); // Retirar \r e guardar a linha consoante o identificador
        }

        return $mappedRows;
    }

    private function checkLines($oldData, $newData)
    {
        $same = [];
        $new = [];
        $updated = [];

        foreach ($newData as $key => $value) {
            if (isset($oldData[$key])) { // Verificar se o identificar da linha existe nos dados antigos
                if($oldData[$key] === $value) {
                    array_push($same, $value); // Se existir, enviar para o array dos campos que se mantem o mesmo
                } else {
                    array_push($updated, $value); // Nao existindo, enviar para o array de dados que foram atualizados
                }
            } else {
                array_push($new, $value); // Se o identificar nao existir nos dados antigos, signficada que sao dados novos
            }
        }

        return [
            "same" => $same,
            "new" => $new,
            "updated" => $updated
        ];
    }
}
<?php

namespace Tests\Unit;

use App\Services\CsvService;
use Tests\TestCase;

class CsvServiceTest extends TestCase
{
    public function test_differences_are_correct(): void
    {
        $oldCsv = "cnpj;pdf_file_name;balance_date;balance_refers_to_date;ativo_total;ativo_circulante;ativo_nao_circulante;passivo_total;passivo_circulante;passivo_nao_circulante;patrimonio_liquido;receita_liquida;lucro_bruto;
        802000100;DODF_2019_5_31_561060_2018.pdf;31-mai-19;31-dez-18;R$ 163.979.827,09;R$ 155.279.290,81;R$ 8.700.536,28;R$ 163.979.827,09;R$ 30.618.990,79;R$ 0;R$ 133.360.836,3;R$ 361.745.884,06;R$ 77.397.501,05;
        1959772000118;DOPR_2015_6_2_394068_2014.pdf;02-jun-15;31-dez-14;R$ 759.305.000;R$ 86.587.000;R$ 672.718.000;R$ 759.305.000;R$ 13.582.000;R$ 346.664.000;R$ 399.059.000;R$ 132.185.000;R$ 126.562.000;
        1722256000175;DOSP_2016_7_15_516575_2015.pdf;15-jul-16;31-dez-15;R$ 376.195.000;R$ 153.071.000;R$ 113.124.000;R$ 266.195.000;R$ 65.828.000;R$ 33.184.000;R$ 167.183.000;R$ 275.241.000;R$ 58.403.000;";
        $newCsv = "cnpj;pdf_file_name;balance_date;balance_refers_to_date;ativo_total;ativo_circulante;ativo_nao_circulante;passivo_total;passivo_circulante;passivo_nao_circulante;patrimonio_liquido;receita_liquida;lucro_bruto;
        802000100;DODF_2019_5_31_561060_2018.pdf;31-mai-19;31-dez-18;R$ 163.979.827,09;R$ 155.279.290,81;R$ 8.700.536,28;R$ 163.979.827,09;R$ 30.618.990,79;R$ 0;R$ 133.360.836,3;R$ 361.745.884,06;R$ 77.397.501,05;
        1959772000118;DOPR_2015_6_2_394068_2014.pdf;02-jun-15;31-dez-14;R$ 759.305.000;R$ 86.587.000;R$ 672.718.000;R$ 759.305.000;R$ 13.582.000;R$ 346.664.000;R$ 399.059.000;R$ 132.185.000;R$ 126.562.000;
        1722256000175;DOSP_2016_7_15_516575_2015.pdf;15-jul-17;31-dez-15;R$ 376.195.000;R$ 153.071.000;R$ 113.124.000;R$ 266.195.000;R$ 65.828.000;R$ 33.184.000;R$ 167.183.000;R$ 275.241.000;R$ 58.403.000;
        1722256000177;DOSP_2016_7_15_516575_2015.pdf;15-jul-16;31-dez-15;R$ 376.195.000;R$ 153.071.000;R$ 113.124.000;R$ 266.195.000;R$ 65.828.000;R$ 33.184.000;R$ 167.183.000;R$ 275.241.000;R$ 58.403.000;";
        $csvService = new CsvService();
        $result = $csvService->checkDifferences($oldCsv, $newCsv);

        $this->assertTrue(count($result['same']) == 2);
        $this->assertTrue(count($result['updated']) == 1);
        $this->assertTrue(count($result['new']) == 1);
    }
}

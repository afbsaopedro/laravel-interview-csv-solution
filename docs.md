# Documentação Serviço CSV

Neste *code challenge* foi criado serviço para comparar dois ficheiros `.csv`, um ficheiro contém os dados antigos e o outro os novos dados, e identificar nas várias entradas se os dados se mantiveram e não foram alterados, se os dados foram actualizados ou se temos novos dados.

Este serviço está implementado numa aplicação Laravel onde está criado um *endpoint* para receber um *request* onde estão incluídos os ficheiros.

O *request* é gerido por um *controller* e o processamento de dados por um serviço.

Foi ainda implementado um teste unitário básico para testar o serviço.

  

## CsvController

A responsabilidade do `CsvController` é gerir os *requests* relacionados com comparação de ficheiros `.csv`. Valida os ficheiros recebidos, lê o conteúdo dos ficheiros e chamada serviço `CsvService` para fazer a comparação.

### Funções

#### `checkDifference(Request $request, CsvService $csvService)`
Esta função compara os ficheiros recebidos no *request*, valida os ficheiros, lê os conteúdos faz uso de um serviço (`CsvService`) para encontrar e retorna as diferenças entre os ficheiros.

##### Parâmetros:
-  `Request $request`: *Request* que contém os ficheiros `.csv`
-  `CsvService $csvService`: Serviço the faz a comparação em si

##### Respostas:
-  **Successo**: Retorna um objecto JSON com as difereças categorizadas como **same**, **new** e **updated** juntamente com o `status code 200`
-  **Erro de Validação**: Retorna um objecto JSON com os erros. Feito através de um validador incluido no Laravel

## CsvService
Este serviço contém a lógica para processar e comprar os ficheiros `.csv`.

### Constants
-  `NEW_LINE_DELIMITER`: Delimitador de novas linhas (`\n`)
-  `CSV_DELIMITER`: Delimitador dos dados do ficheiro `.csv` (`;`)
-  `CARRIAGE_DELIMITER`: Delimitador *carriage return* (`\r`)

### Funções

#### `checkDifferences($oldData, $newData)`
Esta função compara os ficheiros verificando as as diferenças entre as várias linhas. Retorna lista dos dados que são são iguais e se mantiveram, novos dados e os dados atualizados.

##### Parâmetros:
-  `$oldData`: Conteúdo do ficheiro dos dados antigos recebido e lido pelo *controller*
-  `$newData`: Conteúdo do ficheiro dos novos dados recebido e lido pelo *controller*

##### Return Values:
-  **Objecto com três *arrays***:
-  **same**: Linhas onde a informação se manteve
-  **new**: Linhas que apenas existem no novo ficheiro, ou seja, dados novos
-  **updated**: Linhas onde a informação foi actualizada

Esta estrutura é por sua vez é retornada pela função `checkLines`

  

#### `getCsvData($data)`
Faz *parse* dos conteúdo de um dos ficheiros `.csv`, Mapeia cada linha a um identificador único criado pela combinação da primeira, segunda, terceira e quarta colunas. Remove o *header* e outros delimitadores.

##### Parâmetros:

-  `$data`: Dados contidos num dos ficheiros recebidos


##### Return Values:
- Objecto onde a `key` é um identificador composto baseado nas quatro primeiras colunas do ficheiro `.csv` a ser interpretado e o `value` é a linha na integra.

##### Flow
-  **Dividir em linhas**: Dividir os conteúdo do ficheiro em linhas individuais tendo em consideração o *delimiter* - `\n`
-  **Remover header**: Remover o *header* do ficheiro. Corresponde a primeira linha
-  **Mapear as linhas**: Por cada linha, separar as colunas e criar um identificar único apartir das primeiras quatro colunas
-  **Devolver dados**: Remover `carriage return` e devolver os dados mapeados em forma de objecto




#### `checkLines($oldData, $newData)`
Compara cada linha dos dois ficheiros e categoriza cada um em três grupos distintos:
-  **same**: Onde a informação se manteve
-  **new**: Dados novos
-  **updated**: Onde a informação foi actualizada

##### Parâmetros:
-  `$oldData`: Objecto que contém as linhas do `.csv` dos dados antigos
- `$newData`: Objecto que contém as linhas do `.csv` dos novos dados

##### Return Values:
- Objecto de *Arrays* de *strings* dividos em:
	-  **same**: Onde a informação se manteve
	-  **new**: Dados novos
	-  **updated**: Onde a informação foi actualizada
As linhas de cada *array* são as linhas onde a informação não sofreu alterações, onde a informação actualizada ou é uma lista das linhas que representam novos dados.

## CsvServiceTest
Este teste foi feito para testar a funcionalidade do serviço de CSV e tem o propósito de verificar se o serviço consegue identificar correctamente as diferenças entre os dois ficheiros `.csv`, mais precisamente se consegue, em ambos os ficheiros, reconhecer a mesma linha, se uma linha foi actualizada ou se a linha é nova e apenas existe no ficheiro de novos dados.
Os dados utilizados foram copiados e alterados à mão dos ficheiros `.csv` fornecidos de modo a garantir que existem pelo menos uma diferença, uma actualização de uma linha de dados e uma linha de dados completamente nova.
As diferenças nos dados do teste são:
- duas linhas com os mesmo dados
- uma linha actualizada
- uma linha nova

### Dados e execução
- `$oldCsv`: Contém 3 linhas de dados. Representa o ficheiro antigo.
- `$newCsv`: Contém 4 linhas de dados com as diferenças acima mencionadas. Representa o ficheiro novo.
- `$csvService =  new  CsvService();`: Cria uma instância do serviço `CsvService`
- `$result = $csvService->checkDifferences($oldCsv, $newCsv);`: Utiliza os dados manuais e chama a função que determinada as funções entre os dados.
- `$this->assertTrue(count($result['same'])  ==  2);`: Verifica se existem duas entradas no *array* `same`
- `$this->assertTrue(count($result['updated'])  ==  1);`: Verifica se existem duas entradas no *array* `updated`
- `$this->assertTrue(count($result['new'])  ==  1);`: Verifica se existem duas entradas no *array* `new`
  

## Utilizar e testar o serviço
- Navegar até ao directório da aplicação
- Levantar a aplicação: `sail up -d`
- Fazer um *request* através do **Postman** ou programa similar. Estrutura do *request*:
-  `{ old_data: ficheiro-csv-dados-antigos-aqui.csv, new_data: ficheiro-csv-novos-dados-aqui.csv}`




**Teste**: `sail artisan test`
**NOTA****: `sail` entende-se como `sh $([ -f sail ] && echo sail || echo vendor/bin/sail)`
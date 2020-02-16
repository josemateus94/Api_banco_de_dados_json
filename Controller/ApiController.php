<?php
include_once(dirname(__DIR__)."/Model/Api.php");
class ApiController{

    public function show(int $id = null){
        $api = new Api();
        $result = $api->pesquisarId($id);

        if (empty($result)) {
            $this->jsonResponse(false, 'Id de Serie não encontrada', $result);    
        }        
        $this->jsonResponse(true, "Pesquisa referente o Id - {$id}", $result);
    }

    public function index(){
        $api = new Api();
        $this->jsonResponse(true, 'Registos do banco', $api->list());
    }

    public function create($serie){
        if (empty($serie)) {
            $this->jsonResponse(false, 'Dados de cadastro não informado');
        }
        $api = new Api();
        $result = $api->pesquisarId($serie['ID']);
        
        if (!empty($result)) {
            $this->jsonResponse(false, 'Id da serie já cadastrada', $result);
        }
        $serie_atual = $api->list();
        $serie['DATA_CADASTRO'] = date("Y-m-d H:i");
        array_push($serie_atual['SERIES'], $serie);
        $result = $api->save($serie_atual);
        if ($result) {
            $this->jsonResponse(true, 'Serie cadastrada com sucesso!', $api->list());
        }else{
            $this->jsonResponse(false, "Erro ao salvar os arquivos", $api->list());
        }
        
    }

    public function destroy(array $serie){
        $api    = new Api();        
        $result = $api->pesquisarId($serie['ID']);
        
        if (empty($result)) {
            $this->jsonResponse(false, 'Id não cadastrada', $api->list());
        }
        $id_serie_edit   = key($result);
        $serie_atual     = $api->list();
        unset($serie_atual['SERIES'][$id_serie_edit]);        
        $result = $api->save($serie_atual);

        if ($result) {
            $this->jsonResponse(true, "Serie deletada com sucesso!", $api->list());
        }else{
            $this->jsonResponse(false, "Erro ao salvar os arquivos", $api->list());
        }
        
    }
    public function edit(array $serie){

        $api = new Api();
        $result = $api->pesquisarId($serie['ID']);
        
        if (empty($result)) {
            $this->jsonResponse(false, 'Id não cadastrada', $api->list());
        }
        $id_serie_edit   = key($result);
        $serie_atual     = $api->list();
        $serie_atual['SERIES'][$id_serie_edit]['NOME'] =  $serie['NOME'];
        $serie_atual['SERIES'][$id_serie_edit]['GENERO'] =  $serie['GENERO'];

        $result = $api->save($serie_atual);
        if ($result) {
            $this->jsonResponse(true, 'Serie editada com sucesso!', $api->list());
        }else{
            $this->jsonResponse(false, "Erro ao salvar os arquivos", $api->list());
        }
    }

    private function jsonResponse($erro, $mgs = null, array $arrayCont = null){
        if ($erro) {
            header("HTTP/1.0 200 ok");
            echo json_encode(array('SUCESSO'=>$erro, "MGS" => $mgs, "REGISTROS" => $arrayCont));
            exit;
        }else{
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(array('SUCESSO'=>$erro, "MGS" => $mgs, "REGISTROS" => $arrayCont));
            exit;
        }
    }
}

?>
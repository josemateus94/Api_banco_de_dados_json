<?php

class Api{

    private $caminho;
    public function __construct(){
        $this->caminho = (dirname(__DIR__)."/Json/db.json");
    }
    public function list(){
        if (!file_exists($this->caminho)) {
            return false; json_encode(array("SUCESSO" => false, "mgs" => 'Arquivo de banco não encontrado!'));
        }
        $this->json = file_get_contents($this->caminho);
        return json_decode($this->json, true);
    }

    public function pesquisarId(int $id){
        return (array_filter($this->list()['SERIES'], function ($var) use ($id){
            return ($var['ID'] == $id);
            })
        );
        
    }

    public function save(array $serie){        
        return file_put_contents($this->caminho, json_encode($serie));
    }
}

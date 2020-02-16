<?php
    include_once("cabecalho.php");
    include_once("./Controller/ApiController.php");
    
    $controller = new ApiController();
    $method     = $_SERVER['REQUEST_METHOD'];    
    $serie      = file_get_contents("php://input");
    $serie      = json_decode($serie, true);
    
    switch ($method) {
        case 'GET':
            $id_serie = (int)filter_input(INPUT_GET, 'id_serie', FILTER_DEFAULT);
            if (!empty($id_serie)) {
                $controller->show($id_serie);
            }
            $controller->index();
            break;        
        case 'POST':
            $serie = ($controller->create($serie));
            break;
        case 'PUT':
            $serie = ($controller->edit($serie));
            break;
        case 'DELETE':
            $serie = ($controller->destroy($serie));
            break;
    }    
?>
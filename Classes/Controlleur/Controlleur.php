<?php
namespace Controlleur;


class Controlleur
{
    protected array $params;
    public function __construct(array $params = [])
    {
       $this->params = $params;
    }


     public function render(string $path, array $params = []){
         extract($params);
         require "./view/".$path; 
     }

    public function redirect(string $controller, string $action, int $id = null){
        if($id){
            header("Location: index.php?controller=$controller&action=$action&id=$id");
        }else{
            header("Location: index.php?controller=$controller&action=$action");
        }
        exit;
    }
}
<?php

class Application_Model_Newsletter {
    
    protected $Id;
    protected $Titulo;
    protected $Noticias;

    public function getId() {
        return $this->Id;
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function getNoticias() {
        return $this->Noticias;
    }

    public function setId($Id) {
        $this->Id = $Id;
        return $this;
    }

    public function setTitulo($Titulo) {
        $this->Titulo = $Titulo;
        return $this;
    }

    public function setNoticias($Noticias) {
        $this->Noticias = $Noticias;
        return $this;
    }

    public function addNoticia($Noticia) {
        if (!is_array($this->Noticias)) {
            $this->Noticias = array();
        }

        if (!array_search($Noticia, $this->Noticias)) {
            array_push($this->Noticias, $Noticia);
        }
    }

}

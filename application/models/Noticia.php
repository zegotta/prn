<?php

class Application_Model_Noticia {
    
    const URL_XML = "http://prncloud.com/xml/rss_generico.php?clienteNews=277&paisNews=8";

    protected $Id;
    protected $CodigoPrn;
    protected $Titulo;
    protected $Link;
    protected $Categorias;
    protected $Descricao;
    protected $Media;
    protected $Autor;
    protected $DataPublicacao;

    public function getId() {
        return $this->Id;
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function getLink() {
        return $this->Link;
    }

    public function getCategorias() {
        return $this->Categorias;
    }

    public function getDescricao() {
        return $this->Descricao;
    }

    public function getMedia() {
        return $this->Media;
    }

    public function getAutor() {
        return $this->Autor;
    }

    public function getDataPublicacao() {
        return $this->DataPublicacao;
    }

    public function setId($Id) {
        $this->Id = $Id;
        return $this;
    }

    public function setTitulo($Titulo) {
        $this->Titulo = $Titulo;
        return $this;
    }

    public function setLink($Link) {
        $this->Link = $Link;
        return $this;
    }

    public function setCategorias($Categorias) {
        $this->Categorias = $Categorias;
        return $this;
    }

    public function setDescricao($Descricao) {
        $this->Descricao = $Descricao;
        return $this;
    }

    public function setMedia($Media) {
        $this->Media = $Media;
        return $this;
    }

    public function setAutor($Autor) {
        $this->Autor = $Autor;
        return $this;
    }

    public function setDataPublicacao($DataPublicacao) {
        $this->DataPublicacao = $DataPublicacao;
        return $this;
    }
    
    public function getCodigoPrn() {
        return $this->CodigoPrn;
    }

    public function setCodigoPrn($CodigoPrn) {
        $this->CodigoPrn = $CodigoPrn;
        return $this;
    }

    public function addCategoria($Categoria) {
        if (!is_array($this->Categorias)) {
            $this->Categorias = array();
        }

        if (!array_search($Categoria, $this->Categorias)) {
            array_push($this->Categorias, $Categoria);
        }
    }

    /**
     * Faz a leitura da URL do XML para identificar as notícias
     */
    function buscaNoticiasNoCanal($url, $listaId = NULL) {
        $xml = simplexml_load_file($url, null, LIBXML_NOCDATA)->channel;

        $retorno = array();

        foreach ($xml->item as $item) {
            $codigoPrn = $this->identificaCodigoPrn($item->link->__toString());

            if (is_array($listaId) && array_search($codigoPrn, $listaId) === FALSE) {
                continue;
            }
            $descricao = $this->exploraDescricao($item->description->__toString());

            $Noticia = new self();
            $Noticia->setCodigoPrn($codigoPrn)
                    ->setTitulo(trim($item->title->__toString()))
                    ->setLink($item->link->__toString())
                    ->setDescricao($descricao['Abstract'])
                    ->setMedia($descricao['Media'])
                    ->setAutor($descricao['Author'])
                    ->setDataPublicacao($descricao['Publication Date']);

            foreach ($item->category as $categoria) {
                $Noticia->addCategoria(trim($categoria->__toString()));
            }

            $retorno[] = $Noticia;
        }
        return $retorno;
    }

    /**
     * Quebra o link da notícia para identificar o ID dela
     */
    private function identificaCodigoPrn($link) {
        list(, $queryString) = explode("?", $link, 2);
        $parametros = explode("&", $queryString);

        foreach ($parametros as $parametro) {
            list($chave, $valor) = explode("=", $parametro, 2);
            if (strtolower($chave) === "id") {
                return $valor;
            }
        }
    }

    /**
     * Quebra a descrição da notícia para identificar os campos de informação presentes
     */
    private function exploraDescricao($descricao) {
        $retorno = array();
        $secoes = explode("<br>", $descricao);
        foreach ($secoes as $secao) {
            list($chave, $valor) = explode(":", $secao, 2);
            if (strtolower(strip_tags($chave)) === "author") {
                // Corrige autores que começam com vírgulas
                $vetorAutores = (explode(",", trim(strip_tags($valor))));
                $valor = join(",", array_filter($vetorAutores));
            }

            $retorno[strip_tags($chave)] = trim(strip_tags($valor));
        }

        return $retorno;
    }

}

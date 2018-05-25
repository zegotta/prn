<?php

class Application_Model_NoticiaMapper {

    public function salvar(Application_Model_Noticia $Noticia) {
        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = "INSERT INTO prn_noticia (id, codigo_prn, titulo, link, categorias, descricao, media, autor, dataPublicacao) ";
        $sql .= "VALUES (";
        $sql .= "DEFAULT, ";
        $sql .= "{$Noticia->getCodigoPrn()}, ";
        $sql .= "{$db->quote($Noticia->getTitulo())}, ";
        $sql .= "{$db->quote($Noticia->getLink())}, ";
        $sql .= "{$db->quote(serialize($Noticia->getCategorias()))}, ";
        $sql .= "{$db->quote($Noticia->getDescricao())}, ";
        $sql .= "{$db->quote($Noticia->getMedia())}, ";
        $sql .= "{$db->quote($Noticia->getAutor())}, ";
        $sql .= "'{$Noticia->getDataPublicacao()}'";
        $sql .= ")";

        $rs = $db->query($sql);
        if ($rs !== FALSE) {
            return $db->lastInsertId();
        }

        return FALSE;
    }

    public function buscar($id) {

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = "SELECT * FROM prn_noticia ";
        $sql .= "WHERE id = {$id} ";

        $rs = $db->fetchRow($sql);
        if ($rs === FALSE) {
            return FALSE;
        }

        $Noticia = new Application_Model_Noticia();
        $Noticia->setId($rs['id'])
                ->setCodigoPrn($rs['codigo_prn'])
                ->setTitulo($rs['titulo'])
                ->setMedia($rs['media'])
                ->setLink($rs['link'])
                ->setAutor($rs['autor'])
                ->setDataPublicacao($rs['dataPublicacao'])
                ->setDescricao($rs['descricao']);

        $Categorias = unserialize($rs['categorias']);
        foreach ($Categorias as $categoria) {
            $Noticia->addCategoria($categoria);
        }
        return $Noticia;
    }

    public function buscaOrdenada($vetor) {

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = "SELECT * FROM prn_noticia n ";
        $sql .= "WHERE n.id IN (" . join(", ", array_values($vetor)) . ") ";
        $sql .= "ORDER BY dataPublicacao ASC, titulo ASC ";

        $rs = $db->fetchAll($sql);
        if ($rs === FALSE) {
            return FALSE;
        }

        $retorno = array();
        foreach ($rs as $linha) {
            $Noticia = new Application_Model_Noticia();
            $Noticia->setId($linha['id'])
                    ->setCodigoPrn($linha['codigo_prn'])
                    ->setTitulo($linha['titulo'])
                    ->setMedia($linha['media'])
                    ->setLink($linha['link'])
                    ->setAutor($linha['autor'])
                    ->setDataPublicacao($linha['dataPublicacao'])
                    ->setDescricao($linha['descricao']);

            $Categorias = unserialize($linha['categorias']);
            foreach ($Categorias as $categoria) {
                $Noticia->addCategoria($categoria);
            }
            
            $retorno[] = $Noticia;
        }
        return $retorno;
    }

}

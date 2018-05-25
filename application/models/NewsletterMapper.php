<?php

class Application_Model_NewsletterMapper {

    public function salvar(Application_Model_Newsletter $Newsletter) {
        $db = Zend_Db_Table::getDefaultAdapter();

        $noticias = array();
        foreach ($Newsletter->getNoticias() as $noticia) {
            array_push($noticias, $noticia->getId());
        }

        $sql = "INSERT INTO prn_newsletter (id, descricao, noticias) ";
        $sql .= "VALUES (";
        $sql .= "DEFAULT, ";
        $sql .= "'{$Newsletter->getTitulo()}', ";
        $sql .= "'" . serialize($noticias) . "' ";
        $sql .= ")";

        $rs = $db->query($sql);
        if ($rs !== FALSE) {
            return $db->lastInsertId();
        }

        return FALSE;
    }

    public function buscar($id) {
        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = "SELECT * FROM prn_newsletter ";
        $sql .= "WHERE id = {$id} ";

        $rs = $db->fetchRow($sql);
        if ($rs === FALSE) {
            return FALSE;
        }

        $NoticiaMapper = new Application_Model_NoticiaMapper();
        $Newsletter = new Application_Model_Newsletter();
        
        $Newsletter->setId($rs['id'])
                ->setTitulo($rs['descricao']);
        
        $noticias = $NoticiaMapper->buscaOrdenada(unserialize($rs['noticias']));
        foreach ($noticias as $noticia) {
            $Newsletter->addNoticia($noticia);
        }

        return $Newsletter;
    }

    public function listar() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $NoticiaMapper = new Application_Model_NoticiaMapper();

        $sql = "SELECT * FROM prn_newsletter ";

        $rs = $db->fetchAll($sql);
        if ($rs === FALSE) {
            return FALSE;
        }

        $retorno = array();

        foreach ($rs as $linha) {
            $Newsletter = new Application_Model_Newsletter();

            $Newsletter->setId($linha['id']);
            $Newsletter->setTitulo($linha['descricao']);

            $noticias = unserialize($linha['noticias']);
            foreach ($noticias as $noticiaId) {
                $noticia = $NoticiaMapper->buscar($noticiaId);
                $Newsletter->addNoticia($noticia);
            }

            $retorno[] = $Newsletter;
        }

        return $retorno;
    }

}

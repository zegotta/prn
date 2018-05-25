<?php

class NewsletterController extends Zend_Controller_Action {

    public function init() {
        $this->view->headLink()->appendStylesheet("https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
        $this->view->headLink()->appendStylesheet("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");

        $this->view->headScript()->appendScript("var baseUrl = '" . $this->view->baseUrl() . "';");
        $this->view->headScript()->appendFile("https://code.jquery.com/jquery-3.2.1.min.js");
//        $this->view->headScript()->appendFile("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js");
        $this->view->headScript()->appendFile("https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js");
    }

    public function criarAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl("/public/js/newsletter/criar.js?tkn=").date("His"));
        $this->_helper->_layout->setLayout('news');
        $noticias = $this->getParam('noticia');

        $Noticia = new Application_Model_Noticia();
        $NoticiaMapper = new Application_Model_NoticiaMapper();

        $listaNoticias = $Noticia->buscaNoticiasNoCanal(Application_Model_Noticia::URL_XML, $noticias);
        foreach ($listaNoticias as $noticia) {
            $id = $NoticiaMapper->salvar($noticia);
            $noticia->setId($id);
            $noticiasInseridas[] = $noticia;
        }

        $this->view->assign("noticias", $noticiasInseridas);
    }

    public function finalizarAction() {
        $this->_helper->_layout->setLayout('news');
        $noticias = $this->getParam('noticia');
        $titulo = $this->getParam('titulo');

        $Newsletter = new Application_Model_Newsletter();
        $NewsletterMapper = new Application_Model_NewsletterMapper();
        $NoticiaMapper = new Application_Model_NoticiaMapper();

        $Newsletter->setTitulo($titulo);
        foreach ($noticias as $noticiaId) {
            $noticia = $NoticiaMapper->buscar($noticiaId);
            $Newsletter->addNoticia($noticia);
        }

        $id = $NewsletterMapper->salvar($Newsletter);
        if ($id !== FALSE) {
            $msg = "Sucesso";
        } else {
            $msg = "Azar";
        }

        $this->view->assign("msg", $msg);
    }

    public function listaAction() {
        $this->_helper->_layout->setLayout('news');

        $NewsletterMapper = new Application_Model_NewsletterMapper();
        $listaNewsletter = $NewsletterMapper->listar();

        $this->view->assign("listaNewsletter", $listaNewsletter);
    }
    
    public function visualizarAction(){
        $this->_helper->_layout->setLayout('news');
        
        $id = $this->getParam("id");
        
        $NewsletterMapper = new Application_Model_NewsletterMapper();
        $newsletter = $NewsletterMapper->buscar($id);
        
        $this->view->assign("newsletter", $newsletter);
        
    }
    
    public function enviarAction (){
        //TODO envio de email
        $this->_helper->_layout->setLayout('news');
    }

}

<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        $this->view->headLink()->appendStylesheet("https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
        $this->view->headLink()->appendStylesheet("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");

        $this->view->headScript()->appendScript("var baseUrl = '" . $this->view->baseUrl() . "';");
        $this->view->headScript()->appendFile("https://code.jquery.com/jquery-3.2.1.min.js");
//        $this->view->headScript()->appendFile("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js");
        $this->view->headScript()->appendFile("https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js");
    }

    public function indexAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl("/public/js/index/index.js?tkn=").date("His"));
        $this->_helper->_layout->setLayout('news');
        $Noticia = new Application_Model_Noticia();

        $listaNoticias = $Noticia->buscaNoticiasNoCanal(Application_Model_Noticia::URL_XML);

        $this->view->assign("listaNoticias", $listaNoticias);
    }

}

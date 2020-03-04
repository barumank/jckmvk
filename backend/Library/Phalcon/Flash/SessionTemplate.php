<?php


namespace Backend\Library\Phalcon\Flash;


use Phalcon\Mvc\View;

class SessionTemplate extends \Phalcon\Flash\Session
{
    /**
     * @var View
     */
    protected $view;
    protected $partialPath;

    const CONTENT_VIEW = 'layout';


    public function __construct($options)
    {
        parent::__construct($options);

        $this->view = $options['view'];
        $this->partialPath = $options['partialPath'];

    }

    public function output($remove = true)
    {
        $response = '';
        $messageList = $this->_getSessionMessages($remove);

        if(empty($messageList) || count($messageList)==0){
            return $response;
        }

        $oldDir = $this->view->getPartialsDir();
        $this->view->setPartialsDir($this->partialPath);
        foreach ($messageList as $type => $messages){
            foreach ($messages as $message){
                $response .= $this->view->getPartial($type,[
                    'message'=>$message
                ]);
            }
        }
        $response = $this->view->getPartial(self::CONTENT_VIEW,[
            'messages'=>$response
        ]);
        $this->view->setPartialsDir($oldDir);

        parent::clear();

        return $response;
    }
    

}
<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 19.3.2018
 * Time: 19:04
 */

abstract class Question
{
    protected $index;
    protected $question;
    protected $note;
    protected $name;
    protected $reqired;

    protected $id;

    protected $valid = true;




    public function __construct($index, $qustion, $note, $name, $rqired)
    {
        $this->index = $index;
        $this->note = $note;
        $this->question = $qustion;
        $this->name = $name;
        $this->reqired = $rqired;
    }

    public function getName() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }

    protected function renderTop() {
        $hb = new HtmlBuilder();
        $hb->openElement("div", array("class" => "question"));
        $hb->openElement("h4");
        $hb->addValue($this->index . '. ' . $this->question);
        $hb->closeElement();
        if($this->note != NULL) {
            $hb->openElement("p");
            $hb->addValue($this->note);
            $hb->closeElement();
        }
        if(!$this->valid) {
            $hb->openElement("p", array('style' => 'color: red'));
            $hb->addValue($this->message);
            $hb->closeElement();
        }
        return $hb->render();
    }

    protected function renderBottom() {
        $hb = new HtmlBuilder();

        $hb->closeElement("div");

        return $hb->render();
    }

    public function setValid($valid) {
        $this->valid = $valid;
    }
    public function setMessage($message) {
        $this->message = $message;
    }
    public function getQuestion() {
        return $this->question;
    }

    protected function renderBadValidation() {
        $hb = new HtmlBuilder();
        $hb->openElement('i', array('class' => 'fa fa-times', 'aria-hidden' => 'true'));
        $hb->closeElement();
        return $hb->render();
    }


    abstract function render();

    abstract function validate();

}
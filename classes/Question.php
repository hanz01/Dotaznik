<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 19.3.2018
 * Time: 19:04
 */

abstract class Question
{
    protected $question;
    protected $note;
    protected $name;
    protected $reqired;

    protected $id;

    protected $valid = true;



    public function __construct($qustion, $note, $name, $rqired)
    {
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
        $hb->openElement("h2");
        $hb->addValue($this->question);
        $hb->closeElement();
        if($this->note != NULL) {
            $hb->openElement("p");
            $hb->addValue($this->note);
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

    public function getQuestion() {
        return $this->question;
    }


    abstract function render();

    abstract function validate();

}
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
    protected $name;
    protected $reqired;

    protected $valid = true;


    public function __construct($qustion, $name, $rqired)
    {
        $this->question = $qustion;
        $this->name = $name;
        $this->reqired = $rqired;
    }
    public function getName() {
        return $this->name;
    }
    protected function renderTop() {
        $hb = new HtmlBuilder();
        $hb->openElement("div", array("class" => "question"));
        $hb->openElement("h2");
        $hb->addValue($this->question);
        $hb->closeElement();
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


    abstract function render();

    abstract function validate();

}
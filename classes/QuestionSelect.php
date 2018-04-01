<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 24.3.2018
 * Time: 16:09
 */

class QuestionSelect extends  Question
{
    private $max;

    protected $oppnions = array();

    protected $posted = array();

    protected $message;

    public function __construct($qustion, $name, $max, $rqired = true)
    {
        parent::__construct($qustion, $name, $rqired);
        $this->max = $max;
        $this->initOppnions();
    }

    public function __construct1($qustion, $name, $max, $rqired = true)
    {
        parent::__construct($qustion, $name, $rqired);
        $this->max = $max;
    }
    private function initOppnions() {
        for($i=0; $i<=5; $i++) {
            $this->oppnions['op-'.$i] = "Možnost " . $i;
        }
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setPosted($posted) {
        $this->posted = $posted;
    }
    public function getData() {
        if($this->max > 1)
            return implode(";", array_keys ($this->posted));
        else
            return $this->posted;
    }

    public function render()
    {
        $i = 0;
        $hb= new HtmlBuilder();
        $type = "radio";
        if($this->max > 1)
            $type = "checkbox";
        foreach($this->oppnions as $k => $v) {

            if($this->max > 1)
                $name = $this->name . "[op".$i."]";
            else
                $name = $this->name;
            $args = array("type" => $type, "name" => $name, "value" => $v, "id" => $k);
            if(count($this->posted) > 0 && $this->max > 1) {
                foreach ($this->posted as $v) {
                    if ($v == $this->oppnions["op-".$i]) {
                        $args["checked"] = "checked";
                        break;
                    }
                }
            }
            if(count($this->posted) == 1 && ($this->posted == $v)) {
                $args["checked"] = "checked";
            }
            $hb->addElemnet("input", $args);
            $hb->openElement("label", array("for" => $k));
            $hb->addValue($v);
            $hb->closeElement();
            $hb->addElemnet("br");
            $i++;
        }
        if(!$this->valid) {
            $hb->openElement("p");
            $hb->addValue($this->message);
            $hb->closeElement();
        }
        $hb->addElemnet("hr");
        return $this->renderTop() . $hb->render() . $this->renderBottom();
    }

    public function validate()
    {
        if($this->reqired) {
            if(count($this->posted) > 0 && count($this->posted) <= $this->max) {
                return true;
            }
            else {
                $this->message = "Musíte zaškrtnout více než 1 položku a méně než " . $this->max;
                $this->setValid(false);
                return false;
            }
        }

    }
}
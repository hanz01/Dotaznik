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

    private $category;

    public function __construct($index, $qustion, $note, $name, $max, $id, $rqired = true, $category = null)
    {
        parent::__construct($index, $qustion, $note, $name, $rqired);
        $this->max = $max;
        $this->id = $id;
        $this->category = $category;
        $this->initOppnions();
    }

    public function __construct1($index, $qustion, $note, $name, $max, $rqired = true)
    {
        parent::__construct($index, $qustion, $note, $name, $rqired);
        $this->max = $max;
    }
    private function initOppnions() {
        if($this->category == null) {
            $op = Db::queryAll('SELECT * FROM moznosti WHERE otazky_id = ?', $this->id);
            $index = 'moznost';
        }
        else {
            $op = Db::queryAll('SELECT nazev FROM ' . config::otazky . ' WHERE kategorie = ?', $this->category);
            $index =  'nazev';
        }
        $i=0;
        foreach($op as $o) {
            $this->oppnions['op'.$i] = $o[$index];
            $i += 1;
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
            return implode(";", array_values($this->posted));
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
                foreach ($this->posted as $p) {
                    if ($p == $this->oppnions["op" . $i]) {
                        $args["checked"] = "checked";
                        break;
                    }
                }

            }
            if(count($this->posted) == 1 && ($this->posted == $v)) {
                $args["checked"] = "checked";
            }
            $hb->addElemnet("input", $args);
            $hb->openElement("label");
            $hb->addValue($v);
            $hb->closeElement();
            $hb->addElemnet("br");
            $i++;
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
                $this->message = "Vyber maximálně: " . $this->max . ' možnosti';
                $this->setValid(false);
                return false;
            }
        }
        else {
            return true;
        }


    }
}
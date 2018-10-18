<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 23.8.2018
 * Time: 19:10
 */

class QuestionSelectNumberSet extends QuestionSelectNumber
{

    private $category;

    private $questions = array();


    public function __construct($index, $qustion, $note, $name, $minValue, $maxValue, $label1, $label2, $category, $cancel, $rqired = true)
    {
        parent::__construct($index, $qustion, $note, $name, $minValue, $maxValue, $label1, $label2, $cancel);
        $this->category = $category;
        $this->initQuetions();

    }

    private function initQuetions() {
        $q = Db::queryAll("SELECT nazev FROM ". config::otazky  .  " WHERE kategorie = ?", $this->category);
        foreach ($q as $item) {
            $this->questions[] = $item['nazev'];
        }
    }

    public function setPosted($posted)
    {
        $this->posted = $posted;
    }

    public function render()
    {
        $hb = new HtmlBuilder();
            $hb->openElement("table", array('border' => '0', 'style' => ' max-width: 755px; margin-left: 1rem'));
            //první řádek
            $hb->openElement('tr');
                $hb->openElement('td');
                $hb->addValue('Otázky');
                $hb->closeElement('td');
                $hb->openElement("td", array('align' => 'right', 'style' => 'padding-right: 1rem'));
                $hb->addValue($this->label1);
                $hb->closeElement("td");
                for($i=0; $i<$this->maxValue-2; $i++) {
                    $hb->openElement('td');
                    $hb->closeElement('td');
                }
                $hb->openElement("td", array('align' => 'left', 'style' => 'padding-left: 1rem'));
                $hb->addValue($this->label2);
                $hb->closeElement("td");
                if($this->cancel != "NULL") {
                    $hb->openElement("td");
                    $hb->closeElement("td");
                }
                //řádek s čísly
            $hb->closeElement("tr");

            $hb->openElement("tr");
                $hb->openElement("td", array('align' => 'center'));
                $hb->closeElement("td");
                for($i=$this->minValue; $i<=$this->maxValue; $i++) {
                    $hb->openElement("td", array('align' => 'center'));
                    $hb->addValue($i);
                    $hb->closeElement("td");
                }
                if($this->cancel != "NULL") {
                    $hb->openElement("td", array('align' => 'center'));
                    $hb->addValue("Nepamatuji se");
                    $hb->closeElement("td");
                }
                if(!$this->valid) {
                    $hb->openElement('td');
                    $hb->closeElement('td');
                }
            $hb->closeElement("tr");
                //otázky
        for($radek=0; $radek<count($this->questions); $radek++) :
            $hb->openElement("tr");
                $hb->openElement("td", array('align' => 'left'));
                $hb->addValue($this->questions[$radek]);
                $hb->closeElement("td");
                for($i=$this->minValue; $i<=$this->maxValue; $i++) {
                    $hb->openElement("td", array('align' => 'center'));
                    $args = array("type" => 'radio', "name" => $this->name . '-' . $radek, "value" => $i);
                    if (is_array($this->posted) && count($this->posted) > 0 && $this->posted[$radek] == $i && $this->posted[$radek] != 'x') {
                        $args["checked"] = "checked";
                    }
                    $hb->addElemnet('input', $args);
                    $hb->closeElement("td");
                 }
                if($this->cancel != "NULL") {
                    $hb->openElement("td", array('align' => 'center'));
                    $args1 = array("type" => 'radio', "name" => $this->name . '-' . $radek, "value" => 'Nepamatuji se');
                    if(is_array($this->posted) && count($this->posted) > 0 &&  $this->posted[$radek] == 'Nepamatuji se') {
                        $args1['checked'] = 'checked';
                    }
                    $hb->addElemnet('input', $args1);
                    $hb->closeElement("td");
                }
                if(!$this->valid && $this->posted[$radek] == '-1') {
                    $hb->openElement('td');
                    $hb->openElement('i', array('class' => 'fa fa-times', 'aria-hidden' => 'true', 'style' => 'color: red', 'title' => 'Vyber nějakou možnost z tohoto řádku'));
                    $hb->closeElement('td');
                }
            $hb->closeElement("tr");
        endfor;
        $hb->closeElement("table");
        $hb->addElemnet("hr");
        return $this->renderTop() . $hb->render() . $this->renderBottom();
    }

    public function validate()
    {
        if($this->reqired) {
            if (!in_array('-1', $this->posted) && count($this->posted) == count($this->questions)) {
                return true;
            } else {
                $this->message = 'Vyber prosím v každém řádku jednu možnost';
                $this->setValid(false);

                return false;
            }
        }
        else {
            return true;
        }
    }

    public function getData()
{
    return join(";", $this->posted);
}

    /**
     * @return array
     */
    public function getQuestions()
    {
        return $this->questions;
    }


}
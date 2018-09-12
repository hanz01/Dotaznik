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


    public function __construct($qustion, $note, $name, $minValue, $maxValue, $label1, $label2, $category, $cancel, $rqired = true)
    {
        parent::__construct($qustion, $note, $name, $minValue, $maxValue, $label1, $label2, $cancel);
        $this->category = $category;
        $this->initQuetions();

    }

    private function initQuetions() {
        $q = Db::queryAll("SELECT nazev FROM soutezni_otazky WHERE kategorie = ?", $this->category);
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
            $hb->openElement("table");
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
            $hb->closeElement("tr");
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
            $hb->closeElement("tr");
        endfor;
        $hb->closeElement("table");
        return $this->renderTop() . $hb->render() . $this->renderBottom();
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
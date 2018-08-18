<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 31.3.2018
 * Time: 12:20
 */

final class QuestionSelectNumber extends QuestionSelect
{

    private $maxValue;
    private $minValue;

    private $label1;
    private $label2;

    private $cancel;

    public function __construct($qustion, $note, $name, $minValue, $maxValue, $label1, $label2, $cancel = null, $rqired = true)
    {
        parent::__construct1($qustion, $note, $name, 1, $rqired);
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        $this->label1 = $label1;
        $this->label2 = $label2;
        $this->cancel = $cancel;
        $this->initOppnions1();
    }
    private function initOppnions1() {
        for($i=$this->minValue; $i<=$this->maxValue; $i++) {
            $this->oppnions['opp-'.$i] = $i;
        }
        if($this->cancel != "NULL") {
            $this->oppnions['nechci'] = $this->cancel;
        }
    }

    public function render()
    {
        $hb = new HtmlBuilder();
        $type = "radio";
        $i = $this->minValue;
        $hb->openElement("table");
        $hb->openElement("td", array('colspan' => round($this->maxValue / 2)));
        $hb->addValue($this->label1);
        $hb->closeElement("td");
        $hb->openElement("td", array('colspan' => round($this->maxValue / 2)));
        $hb->addValue($this->label2);
        $hb->closeElement("td");
        if($this->cancel != "NULL") {
            $hb->openElement("td");
            $hb->addValue($this->cancel);
            $hb->closeElement("td");
        }
        $hb->openElement("tr");
           foreach ($this->oppnions as $k => $v) {
            $name = $this->name;
            $args = array("type" => $type, "name" => $name, "value" => $v, "id" => $k);
            if (count($this->posted) == 1 && ($this->posted == $v)) {
                $args["checked"] = "checked";
            }
            $hb->openElement("td");
            $hb->addElemnet("input", $args);
            $hb->closeElement();
        }
        $hb->closeElement("tr");
        $hb->openElement("tr");
        $hb->closeElement("tr");
        $hb->openElement("tr");
        for ($i = $this->minValue; $i <= $this->maxValue; $i++)
        {
            $hb->openElement("td");
            $hb->addValue($i);
            $hb->closeElement("td");
        }

        if(!$this->valid) {
            $hb->openElement("p");
            $hb->addValue($this->message);
            $hb->closeElement();
        }
        $hb->closeElement('tr');
        $hb->closeElement('table');
        $hb->addElemnet("hr");
        return $this->renderTop() . $hb->render() . $this->renderBottom();
    }

}
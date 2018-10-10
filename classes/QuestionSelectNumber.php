<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 31.3.2018
 * Time: 12:20
 */

class QuestionSelectNumber extends QuestionSelect
{

    protected $maxValue;
    protected $minValue;

    protected $label1;
    protected $label2;

    protected $cancel;

    public function __construct($index, $qustion, $note, $name, $minValue, $maxValue, $label1, $label2, $cancel = null, $rqired = true)
    {
        parent::__construct1($index, $qustion, $note, $name, 1, $rqired);
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
        //první řádekl
        $i = $this->minValue;
        $hb->openElement("table", array('border' => '0', 'style' => 'max-width: 750px'));
        $hb->openElement("td", array('align' => 'right', 'style' => 'padding-right: 1rem'));
        $hb->addValue($this->label1);
        $hb->closeElement("td");
        for($i=0; $i<count($this->oppnions)-3; $i++) {
            $hb->openElement("td");
            $hb->closeElement("td");
        }
         $hb->openElement("td", array('align' => 'left', 'style' => 'padding-left: 1rem'));
        $hb->addValue($this->label2);
        $hb->closeElement("td");
        if($this->cancel != "NULL") {
            $hb->openElement("td");
            $hb->closeElement("td");
        }
        $hb->closeElement("tr");
        //druhý řádek
        $hb->openElement("tr");
        foreach ($this->oppnions as $k => $v) {
            $name = $this->name;
            $args = array("type" => $type, "name" => $name, "value" => $v, "id" => $k);
            if (count($this->posted) == 1 && ($this->posted == $v)) {
                $args["checked"] = "checked";
            }
            $hb->openElement("td", array('align' => 'center'));
            $hb->addElemnet("input", $args);
            $hb->closeElement();
        }

        $hb->closeElement("tr");
        //čísla
        $hb->openElement("tr");
        for ($i = $this->minValue; $i <= $this->maxValue; $i++)
        {
            $hb->openElement("td", array('align' => 'center'));
            $hb->addValue($i);
            $hb->closeElement("td");
        }
        if($this->cancel != "NULL") {
            $hb->openElement("td");
            $hb->addValue($this->cancel);
            $hb->closeElement("td");
        }


        $hb->closeElement('tr');
        $hb->closeElement('table');
        $hb->addElemnet("hr");
        return $this->renderTop() . $hb->render() . $this->renderBottom();
    }

}
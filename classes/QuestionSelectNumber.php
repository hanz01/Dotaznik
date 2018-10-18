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
        $i=1;
        foreach ($this->oppnions as $k => $v) {
            $name = $this->name;
            $args = array("type" => $type, "name" => $name, "value" => $v, "id" => $k);
            if (count($this->posted) == 1 && ($this->posted == $v)) {
                $args["checked"] = "checked";
            }
            $align = 'center';
            if($i==1) {
                $align = 'right';
            }
            if($i == count($this->oppnions)-1)
                $align = 'left';
            $hb->openElement("td", array('align' => $align));
            $hb->addElemnet("input", $args);
            $hb->closeElement();
            $i +=1;
        }

        $hb->closeElement("tr");
        //čísla
        $hb->openElement("tr");
        for ($i = $this->minValue; $i <= $this->maxValue; $i++)
        {
            $args = array();
            $align = 'center';
            if($i==1) {
                $align = 'right';
                $args['style'] = 'padding-right: 1.2rem';
            }
            if($i == count($this->oppnions)-1) {
                $align = 'left';
                $args['style'] = 'padding-left: 1.2rem';
            }
            $args['align'] = $align;
            $hb->openElement("td", $args);
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
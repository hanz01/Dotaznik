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

    public function __construct($qustion, $name, $minValue, $maxValue, $rqired = true)
    {
        parent::__construct1($qustion, $name, 1, $rqired);
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        $this->initOppnions1();
    }
    private function initOppnions1() {
        for($i=$this->minValue; $i<=$this->maxValue; $i++) {
            $this->oppnions['opp-'.$i] = $i;
        }
    }

    public function render()
    {
        $hb = new HtmlBuilder();
        $type = "radio";
        $i = $this->minValue;
        foreach ($this->oppnions as $k => $v) {
            $name = $this->name;
            $args = array("type" => $type, "name" => $name, "value" => $v, "id" => $k);
            if (count($this->posted) == 1 && ($this->posted == $v)) {
                $args["checked"] = "checked";
            }
            $hb->addElemnet("input", $args);
        }
        $hb->addElemnet("br");
        for ($i = $this->minValue; $i <= $this->maxValue; $i++)
        {
            $hb->openElement("label", array("class" => "label-select-number"));
            $hb->addValue($i);
            $hb->closeElement();
        }

        if(!$this->valid) {
            $hb->openElement("p");
            $hb->addValue($this->message);
            $hb->closeElement();
        }
        $hb->addElemnet("hr");
        return $this->renderTop() . $hb->render() . $this->renderBottom();
    }

}
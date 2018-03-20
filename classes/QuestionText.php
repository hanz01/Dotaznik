<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 19.3.2018
 * Time: 19:06
 */

class QuestionText extends Question
{
    public function __construct($qustion)
    {
        parent::__construct($qustion);
    }

    public function renderFront()
    {
        $hb = new HtmlBuilder();
        $hb->openElement("h2");
        $hb->addValue($this->question);
        $hb->closeElement();
        $hb->addElemnet("input", array("name" => "ot1"));
        $hb->addElemnet("hr");
        return $hb->render();
    }
}
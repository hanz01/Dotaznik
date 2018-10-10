<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 31.3.2018
 * Time: 15:10
 */

class QuestionTextLong extends QuestionText
{
    public function __construct($index, $qustion, $note, $name, $reqired = false, $value = null)
    {
        parent::__construct($index, $qustion, $note, $name, $reqired, $value);
    }

    public function render()
    {
        $params = array("name" => $this->name,  "placeholder" => self::PLACEHOLDER, "class" => 'form-control');
        if($this->reqired)
            $params["required"] = "required";
        if(!$this->valid)
            $params["style"] = "background: red";
        $hb = new HtmlBuilder();
        $hb->openElement("textarea", $params);
        $hb->addValue($this->value);
        $hb->closeElement();
        $hb->addElemnet("hr");
        return $this->renderTop() . $hb->render() . $this->renderBottom();
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 19.3.2018
 * Time: 19:06
 */

class QuestionText extends Question
{
    const PLACEHOLDER = "Zadejte odpověď";

    private $value;
    private $valid = true;

    public function __construct($qustion, $name, $value = null, $reqired = true)
    {
        parent::__construct($qustion, $name, $reqired);
    }

    public function setValue($value) {
        $this->value = $value;
    }
    public function setValid($valid) {
        $this->valid = $valid;
    }


    public function render()
    {
        $params = array("name" => $this->name,  "placeholder" => self::PLACEHOLDER);
        if($this->reqired)
            $params["rrequired"] = "required";
        if(!$this->valid)
            $params["style"] = "background: red";
        if($this->value != null)
            $params["value"] = $this->value;
        $hb = new HtmlBuilder();
        $hb->addElemnet("input", $params);
        $hb->addElemnet("hr");
        return $this->renderTop() . $hb->render();
    }

    public function validate()
    {
        if($this->reqired) {
            return $this->value != null;
        }
        return true;

    }
}
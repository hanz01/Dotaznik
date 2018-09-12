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

    protected $value;

    public function __construct($qustion, $note, $name, $reqired = false, $value = null)
    {
        parent::__construct($qustion, $note, $name, $reqired);
    }

    public function setValue($value) {
        $this->value = $value;
    }
    public function getData() {
        if($this->value == "")
            return 'NULL';
        else
            return $this->value;
    }



    public function render()
    {
        $params = array("type" => "text","name" => $this->name,  "placeholder" => self::PLACEHOLDER);
        if($this->reqired)
            $params["required"] = "required";
        if(!$this->valid)
            $params["style"] = "background: red";
        if($this->value != null)
            $params["value"] = $this->value;
        $hb = new HtmlBuilder();
        $hb->addElemnet("input", $params);
        $hb->addElemnet("hr");
        return $this->renderTop() . $hb->render() . $this->renderBottom();
    }

    public function validate()
    {
        if($this->reqired) {
            return $this->value != null;
        }

    }
}
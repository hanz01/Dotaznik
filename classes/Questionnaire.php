<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 19.3.2018
 * Time: 19:27
 */

class Questionnaire
{
    private $header;
    private $category;
    private $year;

    private $posted;

    private $questions;

    public function __construct($header, $category, $year)
    {
        $this->header = $header;
        $this->category = $category;
        $this->year = $year;
        $this->posted = isset($_POST['form-name']) && $_POST['form-name'] == $this->header;
        $this->initQuestions();

    }

    public function isPosted() {
        return $this->posted;
    }

    private function initQuestions() {
        for($i=1; $i<3; $i++) {
            $q = new QuestionText("Text otázky " . $i, "text" . $i, true);
            $this->questions[] = $q;
        }
        $this->questions[] = new QuestionSelect("Otázka s možností výběru", "vyb-1", 2);
        $this->questions[] = new QuestionSelect("Otázka s možností výběru jedné odpovědi", "vyb-2", 1);

    }

    public function renderHeader() {
        $hb = new HtmlBuilder();
        $hb->openElement("h1");
        $hb->addValue($this->header);
        $hb->closeElement();
        return $hb->render();
    }


    private function renderTop() {
        $hb= new HtmlBuilder();
        $hb->addElemnet("input", array("type" => "hidden", "name" => "form-name", "value" => $this->header));
        return $hb->render();
    }

    private function renderBottom() {
        $hb = new HtmlBuilder();
        $hb->addElemnet("input", array("type" => "submit", "value" => "Odeslat dotazník"));
        return $hb->render();
    }

    public function render() {
        $top = $this->renderTop();
        $bottom = $this->renderBottom();
        $output = "";
        for($i=0; $i<count($this->questions); $i++) {
          $output .= $this->questions[$i]->render();
        }
        return $top . $output . $bottom;
    }

    public function validateForm() {
        $valid = true;
        foreach($this->questions as $k => $v) {
            $name = $this->questions[$k]->getName();
            if($this->questions[$k] instanceof QuestionText) {
                $this->questions[$k]->setValue($_POST[$name]);
                if (!$this->questions[$k]->validate()) {
                    $this->questions[$k]->setValid(false);
                    $valid = false;
                }
            }
            else if($this->questions[$k] instanceof QuestionSelect) {
                if(isset($_POST[$name])) {
                    $this->questions[$k]->setPosted($_POST[$name]);
                    if(!$this->questions[$k]->validate()) {
                        $valid = false;
                    }
                }
                else {
                    $this->questions[$k]->setValid(false);
                    $this->questions[$k]->setMessage("Vyberte aspoň jednu možnost.");
                    $valid = false;
                }

            }
        }
    }
}
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

    private $questions;

    public function __construct($header, $category, $year)
    {
        $this->header = $header;
        $this->category = $category;
        $this->year = $year;
        $this->initQuestions();

    }

    private function initQuestions() {
        for($i=1; $i<10; $i++) {
            $q = new QuestionText("Text otázky" . $i);
            $this->questions[] = $q;
        }
    }

    public function renderTop() {
        $hb= new HtmlBuilder();
        $hb->openElement("h1");
        $hb->addValue($this->header);
        $hb->closeElement();
        return $hb->render();
    }

    public function render() {
        $top = $this->renderTop();
        $output = "";
        for($i=0; $i<count($this->questions); $i++) {
          $output .= $this->questions[$i]->renderFront();
        }
        return $top . $output;
    }
}
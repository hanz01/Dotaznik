<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 19.3.2018
 * Time: 19:27
 */

class Questionnaire
{
    private $id;
    private $header;
    private $category;
    private $year;

    private $posted;

    private $questions;

    private $podminky;

    public function __construct($header, $info, $category, $year, $id, $podminky = 'NULL')
    {
        $this->header = $header;
        $this->info = $info;
        $this->category = $category;
        $this->year = $year;
        $this->posted = isset($_POST['form-name']) && $_POST['form-name'] == $this->header;
        $this->id = $id;
        $this->podminky = $podminky;
        $this->initQuestions();

    }

    public function isPosted() {
        return $this->posted;
    }



    private function initQuestions() {
        $moznosti = Db::queryAll('SELECT * FROM otazky WHERE dotaznik_id = ? ORDER BY otazky_id', $this->id);
        if(count($moznosti) == 0)
            return false;
        $i = 1;
        foreach($moznosti as $m) {
            $povinne = true;
            if($m['povinne'] == 0)
                $povinne = false;

            switch($m['typ']) {
                case 'kratka' :
                    $this->questions[] = new QuestionText($i, $m['otazka'], $m['doplneni'], 'ot-'. $i, $povinne);
                    break;
                case 'dlouha' :
                    $this->questions[] = new QuestionTextLong($i, $m['otazka'], $m['doplneni'], 'ot-'. $i, $povinne);
                    break;
                case '1':
                    $this->questions[] = new QuestionSelect($i, $m['otazka'], $m['doplneni'], 'ot-'. $i, $m['typ'], $m['otazky_id'], $povinne);
                    break;
                case ($m['typ'] == '2' || $m['typ'] == '3' || $m['typ'] == '4') :
                    $this->questions[] = new QuestionSelect($i, $m['otazka'], $m['doplneni'], 'ot-'. $i, $m['typ'], $m['otazky_id'], $povinne);
                    break;
                case 'vyber-auto' :
                    $this->questions[] = new QuestionSelect($i, $m['otazka'], $m['doplneni'], 'ot-'. $i, $m['label2'], $m['otazky_id'], $povinne, $this->category);
                    break;
                case ($m['typ'] == 'sada2' || $m['typ'] == 'sada1') :
                    if($m['typ'] == 'sada1') {
                        $min = 1;
                        $max = 6;
                    }
                    else {
                        $min = 1;
                        $max = 5;
                    }
                    $this->questions[] = new QuestionSelectNumberSet($i, $m['otazka'], $m['doplneni'], 'ot-'. $i, $min, $max, $m['label1'], $m['label2'], $this->category, $m['cancel'], $povinne);

                    break;
                default :
                    $cisla = explode("-", $m['typ']);
                    $min = $cisla[0];
                    $max = $cisla[1];
                    $this->questions[] = new QuestionSelectNumber($i, $m['otazka'], $m['doplneni'], 'ot-'. $i, $min, $max, $m['label1'], $m['label2'], $m['cancel'], true);
                    break;
            }
            $this->questions[$i-1]->setId($m['otazky_id']);
            $i++;

        }
        /*$
        this->questions[] = new QuestionText("Text otázky ", "text", "text", true);
        $this->questions[] = new QuestionTextLong("Text otázky ", "text", "text2", true);
        $this->questions[] = new QuestionSelect("Otázka s možností výběru", "text", "vyb-1", 2);
        $this->questions[] = new QuestionSelect("Otázka s možností výběru jedné odpovědi", "text", "vyb-2", 1);
        $this->questions[] = new QuestionSelectNumber("Otázka s možností výběru jedné odpovědi", "text", "vyb-3", 1, 5, true);
        */
    }

    public function renderHeader() {
        $hb = new HtmlBuilder();
        $hb->openElement("h1");
        $hb->addValue($this->header);
        $hb->closeElement();
        $hb->openElement("p");
        $hb->addValue($this->info);
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
        $hb->addElemnet('div', array('class' => 'text-ceter submit'));
        $hb->addElemnet("input", array("type" => "submit", "value" => "Odeslat dotazník", "class" => "btn btn-danger", 'style' => 'margin: 0 auto'));
        $hb->closeElement();
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
        $pocetSada = 1;
        foreach($this->questions as $k => $v) {
            $name = $this->questions[$k]->getName();
            if($this->questions[$k] instanceof QuestionText) {
                $this->questions[$k]->setValue($_POST[$name]);
                if(!$this->questions[$k]->validate()) {
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
                    $this->questions[$k]->setMessage("Vyberte prosím minimálně jednu možnost");
                    $valid = false;
                }
                if($this->questions[$k] instanceof QuestionSelectNumberSet) {
                       $pocet = count($this->questions[$k]->getQuestions());
                       @$this->questions[$k]->setPosted($_POST[$name . "-" . $pocetSada], $name . "-" . $pocetSada);
                       for($i=0; $i<$pocet; $i++) {
                           if(!empty($_POST[$name . "-" . $i]))
                                $data[] = $_POST[$name . "-" . $i];
                           else
                               $data[] = '-1';
                           $this->questions[$k]->setPosted($data);
                       }
                       unset($data);

                       $valid = $this->questions[$k]->validate();


                }

            }

        }
        return $valid;

    }

    public function getData() {
        $data = array();
        foreach ($this->questions as $item) {
            if(is_array($item->getData()))
                $temp = '-1';
            else
                $temp = $item->getData();
            $data[$item->getId()] = $temp;
        }
        return $data;
    }

    public function getId() {
        return $this->id;
    }
    public function getPodminky() {
        return $this->podminky;
    }
}
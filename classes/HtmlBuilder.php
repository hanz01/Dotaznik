<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 18.3.2018
 * Time: 17:43
 */

class HtmlBuilder
{
    private $output;
    private $openElemnts = array();

    private function renderElement($name, $htmlParams, $pair)
    {
        $this->output .= '<' . htmlspecialchars($name);
        if($htmlParams != null) {
            foreach ($htmlParams as $key => $value) {
                $this->output .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
            }
        }
        if (!$pair)
            $this->output .= ' /';
        $this->output .= '>';
        if ($pair)
            array_push($this->openElemnts, $name);

    }

    public function addElemnet($name, $params = array()) {
        $this->renderElement($name, $params, false);
    }

    public function openElement($name, $params = null) {
        $this->renderElement($name, $params, true);
    }

    public function addValue($value, $doNotEscape = false) {
        $this->output .= $doNotEscape ? $value : htmlspecialchars($value);
    }

    public function closeElement($name = null) {
        if(!$name) {
            $name = array_pop($this->openElemnts);
        }
        $this->output .= "</" . htmlspecialchars($name) . ">\n";
    }

    public function addValueElement($name, $value, $params = array(), $doNotEscape = false) {
        $this->openElemnt($name, $params, true);
        $this->addValue($value, $doNotEscape);
        $this->closeElement();
    }

    public function render() {
        return $this->output;
    }
}
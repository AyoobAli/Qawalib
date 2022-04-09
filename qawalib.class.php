<?php

/**
 * Name: Qawalib
 * Description: Qawalib is a light and powerful Template Engine.
 * Author: Ayoob Ali
 * Website: www.Ayoob.ae
 * License: GNU GPLv3
 * Version: v0.1.3
 * Date: 2022-04-10
 */

/**
    Qawalib is a light and powerful Template Engine for PHP
    Copyright (C) 2021 Ayoob Ali

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program. If not, see <https://www.gnu.org/licenses/>
*/


class Qawalib
{

    private $templatePath   = "template";
    private $theme          = "default";
    private $language       = "en";
    private $fullPath       = "template/default/en/";
    private $variables      = [];
    private $loopVars       = "";
    private $output         = "";
    private $verboseLevel   = 1;
    private $isHTML         = false;
    private $lastMessage    = "";


    ### 
    ### Class Construct
    ###
    function __construct($templatePath = "template", $theme = "default", $language = "en") {
        $this->setTemplate($templatePath, $theme, $language);
        $this->resetVariables();
        $this->resetPrivateVariables();
        $this->resetOutput();
    }


    ### 
    ### Class Destruct
    ###
    function __destruct() {
        $this->resetVariables();
        $this->resetPrivateVariables();
        $this->resetOutput();
    }


    ### 
    ### Clean String or Array for Variable Names
    ### 
    public function variableSafe($string = "") {
        if ( is_array($string) === true ) {
            $safeArr = [];
            foreach ($string as $list_key => $list_value) {
                $safeArr[$list_key] = $this->variableSafe($list_value);
            }
        }else{
            $safeArr = trim(preg_replace("/[^\w\-]/", '', $string));
        }
        return $safeArr;
    }


    ### 
    ### Clean String or Array for HTML use
    ### 
    public function htmlSafe($string = "") {
        if ( is_array($string) === true ) {
            $safeArr = [];
            foreach ($string as $list_key => $list_value) {
                $safeArr[$list_key] = $this->htmlSafe($list_value);
            }
        }else{
            $safeArr = htmlentities($string, ENT_QUOTES);
        }
        return $safeArr;
    }


    ### 
    ### Writing Messages
    ###
    private function msg($message = "", $level = 1) {
        $this->lastMessage = $message;
        if ($this->verboseLevel >= $level) {
            if ($this->isHTML == true) {
                echo htmlentities($message, ENT_QUOTES) . "<br>\n";
            }else{
                echo $message . "\n";
            }
            return true;
        }
        return false;
    }


    ### 
    ### Get Last Messages
    ###
    public function getMessage() {
        return $this->lastMessage;
    }


    ### 
    ### Set Verbose Level
    ###
    public function setVerbose($level = 1) {
        $this->verboseLevel = intval($level);
        return $this->verboseLevel;
    }


    ###
    ### Get Verbose Level
    ###
    public function getVerbose() {
        return $this->verboseLevel;
    }


    ### 
    ### Set newline as HTML for messages
    ###
    public function setHTML($html = true) {
        if ($html == true) {
            $this->isHTML = true;
        }else{
            $this->isHTML = false;
        }
        return $this->isHTML;
    }


    ###
    ### Check if a string starts with string/array
    ###
    public function startWith($string = "", $startWith = "") {
        $string = strtolower($string);
        if (is_array($startWith)) {
            foreach ($startWith as $key => $value) {
                $value = strtolower($value);
                if ( substr($string, 0, strlen($value)) === $value ) {
                    return true;
                }
            }
        }else{
            $startWith = strtolower($startWith);
            if ( substr($string, 0, strlen($startWith)) === $startWith ) {
                return true;
            }
        }
        return false;
    }


    ###
    ### Check if a string contains string/array
    ###
    public function isContain($string = "", $contains = "") {
        $string = strtolower($string);
        if (is_array($contains)) {
            foreach ($contains as $key => $value) {
                $value = strtolower($value);
                if ( strpos($string, $value) !== false) {
                    return true;
                }
            }
        }else{
            $contains = strtolower($contains);
            if ( strpos($string, $contains) !== false) {
                return true;
            }
        }
        return false;
    }


    ###
    ### Check if a string match another text/array
    ###
    public function isEq($string = "", $secondStr = "") {
        $string = trim(strtolower($string));
        if (is_array($secondStr)) {
            foreach ($secondStr as $key => $value) {
                $value = trim(strtolower($value));
                if ($string === $value) {
                    return true;
                }
            }
        }else{
            $secondStr = trim(strtolower($secondStr));
            if ($string === $secondStr) {
                return true;
            }
        }
        return false;
    }


    ### 
    ### Set Template Path
    ###
    public function setTemplate($templatePath = "template", $theme = "default", $language = "en") {
        $templatePath = trim(rtrim($templatePath, '/\\\n\r\t'));
        $theme        = $this->variableSafe($theme);
        $language     = $this->variableSafe($language);
        $fullPath     = $templatePath . "/" . $theme . "/" . $language . "/";
        if ( $templatePath == "" || $templatePath === null || is_dir($templatePath) == false ) {
            $this->msg("Error: Wrong template path", 3);
            return false;            
        }
        if ( $theme == "" || $theme === null ) {
            $this->msg("Error: Theme not specified", 3);
            return false;
        }
        if ( $language == "" || $language === null ) {
            $this->msg("Error: Language not specified", 3);
            return false;
        }
        if ( is_dir($fullPath) ) {
            $this->templatePath = $templatePath;
            $this->theme        = $theme;
            $this->language     = $language;
            $this->fullPath     = $fullPath;
            return true;
        }
        $this->msg("Error: Template location not found", 3);
        return false;
    }


    ### 
    ### Set Template theme
    ###
    public function setTheme($theme = "default") {
        $theme     = $this->variableSafe($theme);
        $fullPath     = $this->templatePath . "/" . $theme . "/" . $this->language . "/";
        if ( $theme == "" || $theme === null ) {
            $this->msg("Error: Theme not specified", 3);
            return false;
        }
        if ( is_dir($fullPath) ) {
            $this->theme        = $theme;
            $this->fullPath     = $fullPath;
            return true;
        }
        $this->msg("Error: Template location not found", 3);
        return false;
    }


    ### 
    ### Set Template language
    ###
    public function setLang($language = "en") {
        $language     = $this->variableSafe($language);
        $fullPath     = $this->templatePath . "/" . $this->theme . "/" . $language . "/";
        if ( $language == "" || $language === null ) {
            $this->msg("Error: Language not specified", 3);
            return false;
        }
        if ( is_dir($fullPath) ) {
            $this->language     = $language;
            $this->fullPath     = $fullPath;
            return true;
        }
        $this->msg("Error: Template location not found", 3);
        return false;
    }


    ###
    ### Set Private Variable value
    ###
    private function setPrivateVariable ($name = "", $value = "", $lang = null) {
        $name = $this->variableSafe($name);
        if (empty($name) || $name == null) {
            $this->msg("Error: Private variable name can't be empty", 3);
            return false;
        }
        if (isset($lang) && is_string($lang) && !empty($this->variableSafe($lang))) {
            $this->variables[$lang]["__" . $name] = $value;
        }else{
            $this->variables[$this->language]["__" . $name] = $value;
        }
        return true;
    }


    ###
    ### Get Private variable value
    ###
    private function getPrivateVariable ($name = "") {
        $name = $this->variableSafe($name);
        $name = "__" . $name;
        if ( isset($this->variables[$this->language][$name]) ) {
            return $this->variables[$this->language][$name];
        }elseif ( isset($this->variables['en'][$name]) ) {
            return $this->variables['en'][$name];
        }
        $this->msg("Error: Can't find private variable ($name)", 4);
        return "";
    }


    ###
    ### Set Variable value
    ###
    // Short name function for setVariable()
    public function v($name = "", $value = "", $lang = null) {
        return $this->setVariable($name, $value, $lang);
    }
    // Original function
    public function setVariable ($name = "", $value = "", $lang = null) {
        $name = $this->variableSafe($name);
        $lang = $this->variableSafe($lang);
        if ( $this->startWith($name, "__") ) {
            $this->msg("Error: Variable name can't start with (__)", 3);
            return false;
        }
        if (empty($name) || $name == null) {
            $this->msg("Error: Variable name can't be empty", 3);
            return false;
        }
        if (isset($lang) && is_string($lang) && !empty($lang)) {
            $this->variables[$lang][$name] = $value;
        }else{
            $this->variables[$this->language][$name] = $value;
        }
        return true;
    }


    ###
    ### Get variable value
    ###
    public function getVariable ($name = "") {
        $name = $this->variableSafe($name);
        if ( ! empty($name) && $name != null && isset($this->variables[$this->language][$name]) ) {
            return $this->variables[$this->language][$name];
        }elseif ( ! empty($name) && $name != null && isset($this->variables['en'][$name]) ) {
            return $this->variables['en'][$name];
        }
        $this->msg("Error: Can't find variable ($name)", 3);
        return "";
    }


    ###
    ### Template variable filters
    ###
    private function varFilters($string = "", $filter = "") {
        $filterArr = explode("|", trim($filter, "|"));
        foreach ($filterArr as $key => $value) {
            switch (strtolower(trim($value))) {
                case 'cap':
                case 'caps':
                case 'capital':
                case 'uppercase':
                case 'upper':
                    $string = strtoupper($string);
                    break;

                case 'low':
                case 'lower':
                case 'small':
                case 'lowercase':
                    $string = strtolower($string);
                    break;

                case 'base64decode':
                case 'base64d':
                case 'b64decode':
                case 'b64d':
                    $dCode = base64_decode($string);
                    if ($dCode !== false) {
                        $string = $dCode;
                    }
                    break;

                case 'base64encode':
                case 'base64e':
                case 'b64encode':
                case 'b64e':
                    $string = base64_encode($string);
                    break;

                case 'urlencode':
                case 'urle':
                    $string = urlencode($string);
                    break;

                case 'urldecode':
                case 'urld':
                    $string = urldecode($string);
                    break;

                case 'int':
                case 'num':
                case 'number':
                case 'integer':
                    $string = intval($string);
                    break;
        
                case 'html':
                case 'code':
                    break;

                default:
                    break;
            }
        }
        if ( ! in_array('code', $filterArr) && ! in_array('html', $filterArr) ) {
            $string = $this->htmlSafe($string);
        }
        return $string;
    }


    ###
    ### Callback function to search for loop functions
    ###
    private function loopHandle ($found) {
        if ( is_array($found) && count($found) > 2 ) {
            $loopVar = $this->getVariable($found[1]);
            if ( is_array($loopVar) ) {
                $tplContent = "";
                $loopNum = 0;
                foreach ($loopVar as $key => $value) {
                    $loopNum++;
                    $tmpCon = $found[2];
                    $tmpCon = preg_replace_callback("|<!--\{#LOOP\|\\$([\w\-]+)\}-->(.*)<!--\{#ENDLOOP\|\\$\\1\}-->|si", array($this,'loopHandle'), $tmpCon);
                    $tmpCon = preg_replace("|<!--\{#item\|#\}-->|i", $loopNum, $tmpCon);
                    $tmpCon = preg_replace("|<!--\{#item\|#key\}-->|i", $this->htmlSafe($key), $tmpCon);
                    if (is_string($value)) {
                        $tmpCon = preg_replace("|<!--\{#item\|#item\}-->|i", $this->htmlSafe($value), $tmpCon);
                    }else{
                        $tmpCon = preg_replace("|<!--\{#item\|#item\}-->|i", "", $tmpCon);
                    }
                    $this->loopVars = [];
                    $this->loopVars = $value;
                    $tmpCon = preg_replace_callback("|<!--\{#item\|([\w\-]+)((?:\|([\w\-]+))+)?\}-->|i", array($this,'loopVarHandle'), $tmpCon);
                    $tplContent .= $tmpCon;
                }
                return $tplContent;
            }
            return "";
        }
        return "";
    }


    ###
    ### Callback function to search for variables in a loop
    ###
    private function loopVarHandle ($found) {
        if ( is_array($found) && count($found) > 1 ) {
            $var = "";
            if ( isset($this->loopVars[$found[1]]) && is_string($this->loopVars[$found[1]]) ) {
                $var = $this->loopVars[$found[1]];
            }
            if (count($found) > 2) {
                $var = $this->varFilters($var, $found[2]);
            }else{
                $var = $this->htmlSafe($var);
            }
            return $var;
        }
        return "";
    }


    ###
    ### Callback function to search for variables
    ###
    private function varHandle ($found) {
        if ( is_array($found) && count($found) > 1 ) {
            $var = $this->getVariable($found[1]);
            if (count($found) > 2) {
                $var = $this->varFilters($var, $found[2]);
            }else{
                $var = $this->htmlSafe($var);
            }
            return $var;
        }
        return "";
    }

    ###
    ### Callback function to include templates within templates
    ###
    private function incHandle ($found) {
        if ( is_array($found) && count($found) > 1 ) {
            return $this->render($found[1], false);
        }
        return "";
    }

    ###
    ### Render template file
    ###
    // Short name function for render()
    public function r($name = "", $update = true) {
        return $this->render($name, $update);
    }
    // Original function
    public function render($name = "", $update = true) {
        $this->setPrivateVariable('templatePath', $this->templatePath);
        $this->setPrivateVariable('theme', $this->theme);
        $this->setPrivateVariable('language', $this->language);
        $this->setPrivateVariable('fullPath', $this->fullPath);
        $tplContent = $this->loadTPL($name);
        $splitter = '<!--{#content#}-->';
        if (empty(trim($tplContent))) {
            return false;
        }
        $tplContent = preg_replace_callback("|<!--\{#LOOP\|\\$([\w\-]+)\}-->(.*)<!--\{#ENDLOOP\|\\$\\1\}-->|si", array($this,'loopHandle'), $tplContent);
        $tplContent = preg_replace_callback("|<!--\{\\$([\w\-]+)((?:\|[\w\-]+)+)?\}-->|", array($this,'varHandle'), $tplContent);
        $tplContent = preg_replace_callback("|<!--\{#INC\|([\w\-]+)\}-->|si", array($this,'incHandle'), $tplContent);
        $contentVar = stripos($tplContent, $splitter);
        if ( $contentVar !== false ) {
            $tplContentNew = substr_replace($tplContent, $this->output, $contentVar, strlen($splitter));
            if ( $update == false ) {
                return $tplContentNew;
            }
            $this->output = $tplContentNew;
        }else{
            if ( $update == false ) {
                return $tplContent;
            }
            $this->output .= $tplContent;
        }
        return true;
    }


    ###
    ### Load template file
    ###
    private function loadTPL ($name = "") {
        $name = $this->variableSafe($name);
        $tplPath = $this->fullPath . $name . ".tpl";
        if ( file_exists($tplPath) ) {
            $tplContent = file_get_contents($tplPath);
            return $tplContent;
        }else{
            return "";
        }
    }


    ###
    ### Print rendered template
    ###
    public function printTemplate($container = "", $print = true) {
        if ( ! empty(trim($container)) && $container != null ) {
            $this->render($container);
        }
        $reCon = true;
        if ( $print == true ){
            echo $this->output;
        }else{
            $reCon = $this->output;
        }
        $this->resetOutput();
        return $reCon;
    }


    ###
    ### Reset all variables and set original Private variables
    ###
    public function resetVariables () {
        $year  = $this->getPrivateVariable('year');
        $month = $this->getPrivateVariable('month');
        $day   = $this->getPrivateVariable('day');
        $date  = $this->getPrivateVariable('date');
        $time  = $this->getPrivateVariable('time');
        $timestamp = $this->getPrivateVariable('timestamp');
        $this->variables = [];
        $this->setPrivateVariable('year', $year);
        $this->setPrivateVariable('month', $month);
        $this->setPrivateVariable('day', $day);
        $this->setPrivateVariable('date', $date);
        $this->setPrivateVariable('time', $time);
        $this->setPrivateVariable('timestamp', $timestamp);
        return true;
    }


    ###
    ### Reset Private variables
    ###
    public function resetPrivateVariables () {
        $this->setPrivateVariable('year', date("Y"));
        $this->setPrivateVariable('month', date("m"));
        $this->setPrivateVariable('day', date("d"));
        $this->setPrivateVariable('date', date("Y-m-d"));
        $this->setPrivateVariable('time', date("H:i:s"));
        $this->setPrivateVariable('timestamp', date("Y-m-d H:i:s"));
        return true;
    }

    
    ###
    ### Reset rendered output
    ###
    public function resetOutput () {
        $this->output = "";
        return true;
    }
}

?>
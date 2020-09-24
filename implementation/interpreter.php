<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
//TODO: Remove test implementation
reader("<!DOCTYPE lml 0.1> <e-h2> <p-text><l-de-t-[Fehler 404]><l-en-t-[Error 404]></p></e>");
elemreader('hr:<div id="hr">
  </div>
test:Hello World
h2:<h2>$text$</h2>');
class Content {
  private $elements;
  private $parameter;
  
  function __construct() {
    $this->elements = [];
    $this->parameter = [];
  }
  
  function add_element($elem) {
    array_push($this->elements, $elem);
  }
  function add_parameter($param) {
    array_push($this->parameter, $param);
  }
  function get_elements() {
    return $this->elements;
  }
  function get_parameter() {
    return $this->parameter;
  }
}

class Variable {
  private $name;
  private $values;
  
  function __construct() {
    $this->name = "";
    $this->values = [];
  }
  
  function add_value($value) {
    array_push($this->values, $value);
  }
  function set_name($name) {
    $this->name = $name;
  }
  function get_values() {
    return $this->values;
  }
  function get_name() {
    return $this->name;
  }
}

class Parameter {
  private $name;
  private $values;
  
  function __construct() {
    $this->name = "";
    $this->values = [];
  }
  
  function add_value($value) {
    array_push($this->values, $value);
  }
  function set_name($name) {
    $this->name = $name;
  }
  function get_values() {
    return $this->values;
  }
  function get_name() {
    return $this->name;
  }
}

class Element {
  private $name;
  private $parameters;
  
  function __construct() {
    $this->name = "";
    $this->parameters = [];
  }
  
  function add_parameter($param) {
    array_push($this->parameters, $param);
  }
  function set_name($name) {
    $this->name = $name;
  }
  function get_values() {
    return $this->parameters;
  }
  function get_name() {
    return $this->name;
  }
}

class Renderelement {
  private $name;
  private $content;
  
  function __construct() {
    $this->name = "";
    $this->content = "";
  }
  
  function set_name($name) {
    $this->name = $name;
  }
  function set_content($content) {
    $this->content = $content;
  }
  function get_name() {
    return $this->name;
  }
  function get_content() {
    return $this->content;
  }
}

class Value {
  private $language;
  private $approved;
  private $text;
  
  function __construct() {
    $this->language = "0";
    $this->approved = false;
    $this->text = "";
  }
  
  function set_language($lang) {
    $this->language = $lang;
  }
  function set_approved($approved) {
    $this->approved = $approved;
  }
  function set_text($text) {
    $this->text = $text;
  }
  function get_language() {
    return $this->language;
  }
  function get_text() {
    return $this->text;
  }
  function get_approved() {
    return $this->approved;
  }
}

//Input: lml-formattet string
//Output: lml data structure
//The first part sorts each tag-text into an array. The second part analysis each tag independently. For tags with closing mechanism the values are written to objects and added to the data structure when closing tag appears.
function reader ($lml) {
  //This part creates a list of tag-content for later analysis:
  $taglist = [];
  $current_tag_text = "";
  $bracketcounter = 0; //Counts opened brackets, if 0, tag is closed.
  for ($i = 0; $i < mb_strlen($lml); $i++) {
    if (mb_substr($lml, $i, 1) == "<") {
      $bracketcounter++;
    } else if (mb_substr($lml, $i, 1) == ">") {
      $bracketcounter--;
    } else if ($bracketcounter > 0) {
      $current_tag_text = $current_tag_text.mb_substr($lml, $i, 1);
    }
    //Checks if tag with content is closed:
    if ($bracketcounter <= 0 && mb_strlen($current_tag_text) > 1) {
      array_push($taglist, $current_tag_text);
      $current_tag_text = "";
    } //Text between Tags is ignored. Later versions could also read that text to write it back for a comment-function in lml.
  }
  print_r($taglist); //Test output. TODO: Remove later
  //Generate datastructure from tag-list:
  //Tags have a minimum length of 2.
  //TODO: Check each tag, so the string-length is always in a good sharp (not too short).
  $content = new Content();
  $element = new Element();
  $parameter = new Parameter();
  $variable = new Variable();
  $value = new Value();
  //Analyzes one tag after another and populates the objects. When all data is available, object is added to content.
  for ($i = 0; $i < count($taglist); $i++) {
    if (mb_substr($taglist[$i], 0, 1) == "e") { //Handles element tags
      $element = new Element(); //Resets for failsave interpretation
      $elementname = mb_substr($taglist[$i], 2); //remove identifier e- and place for string editing operations.
      $element->set_name($elementname);
      if (mb_substr($elementname, mb_strlen($elementname)-1) == "/") { //This part manages format-elements of the form <e-name/>.
        $elementname = mb_substr($elementname, 0, mb_strlen($elementname)-1);
        $element->set_name($elementname);
        $content->add_element($element);
      }
    } else if (mb_substr($taglist[$i], 0, 1) == "p") { //Handles parameter tags
      $parameter = new Parameter(); //Reset for  failsave interpretation
      $parameter->set_name(mb_substr($taglist[$i], 2)); //remove identifier p-
    } else if (mb_substr($taglist[$i], 0, 1) == "l") { // Handle language tags.
      $languagetag = mb_substr($taglist[$i], 2); //For string analyzing, this is where the interim results are saved.
      $languagestring = ""; //Saves the language of the language tag
      if (mb_strlen($languagetag) >= 5) { //Filter too small tags, they are invalid.
        while (mb_strlen($languagetag) > 4 && mb_substr($languagetag, 0, 1) != "-") {//Get the language. Length check also checks for following string cuts.
          $languagestring = $languagestring.mb_substr($languagetag, 0, 1);
          $languagetag = mb_substr($languagetag, 1);
        }
        $value->set_language($languagestring);
        if (mb_substr($languagetag, 1, 1) == "t") { //Check for translator/content creator's approve.
          $value->set_approved(true);
        } else {
          $value->set_approved(false);
        }
        $languagetag = mb_substr($languagetag, 4, mb_strlen($languagetag)-1); //Cut till content is at string-beginning. and strip last char.
        $value->set_text($languagetag); //Write to value object
        $parameter->add_value($value);
        $variable->add_value($value);
      }
    } else if (mb_substr($taglist[$i], 0, 1) == "v") { // Handle variable tags.
      $variable = new Variable(); //reset again
      $variable->set_name(mb_substr($taglist[$i], 2)); //remove identifier v-
    } else if (mb_substr($taglist[$i], 0, 1) == "/") { //Handle closing tags.
      if (mb_substr($taglist[$i], 1, 1) == "e") {
        $content->add_element($element);
      } else if (mb_substr($taglist[$i], 1, 1) == "p") {
        $element->add_parameter($parameter);
      } else if (mb_substr($taglist[$i], 1, 1) == "v") {
        $content->add_variable($variable);
      }
    }
  }
  echo ("<br><br>");
  print_r($content); //Test output. TODO: Remove later
  return $content;
}

//Input: elml-formattet string
//Output: elml data structure
function elemreader($elml) {
  $elements = [];
  $renderelement = new Renderelement();
  $array = explode("\n", $elml);
  for ($i = 0; $i < count($array); $i++) {
    if (mb_substr($array[$i], 0, 1) == " " || mb_substr($array[$i], 0, 1) == "\t") {
      $renderelement->set_content($renderelement->get_content().$array[$i]);
    } else {
      if ($renderelement->get_name() != "" && $renderelement->get_content() != "") {
        array_push($elements, $renderelement);
        $renderelement = new renderelement();
      }
      $index = strpos($array[$i],":");  // Gets the first index where a colon occours
      if ($index == false) {
        echo "Missing seperator or wrong intent in elml file on line ".$i;
      } else {
        $renderelement->set_name(mb_substr($array[$i], 0, $index)); // Gets the first part (name)
        $renderelement->set_content(mb_substr($array[$i], $index + 1)); // Gets the text part (content)
      }
    }
  }
  if ($renderelement->get_name() != "" && $renderelement->get_content() != "") {
    array_push($elements, $renderelement);
    $renderelement = new renderelement();
  }
  echo "<br />Test<br />";
  print_r($elements); //TODO: remove test output
  return $elements;
}
?>
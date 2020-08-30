<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
reader("<!DOCTYPE lml 0.1> <e-h2> <p-text><l-de-t-[Fehler 404]><l-en-t-[Error 404]></p></e>");
class content {
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

class variable {
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

class parameter {
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

class element {
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

class value {
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
  print_r($taglist); //For debugging  during development, TODO: remove later.
  //Generate datastructure from tag-list:
  //Tags have a minimum length of 2.
  //TODO: Check each tag, so the string-length is always in a good sharp (not too short).
  /*
  var content = {elements:[], variables:[]}; //This is the content-object, which will be returned when populated with data.
  var element = {name:"", parameter:[]}; //This is the default element-object. Please only clone those objects.
  var parameter = {name:"", values:[]}; //This is the default parameter-object. Only clone.
  var variable = {name:"", values:[]}; //This is the default variable-object. Only clone.
  var value = {language:"", approved:false, text:""}; //This is the default value-object. Only clone.
  for (var i = 0; i < taglist.length; i++) {
    if (taglist[i].charAt(0) == "e") { //Handles element tags
      element.parameter = []; //Resets parameter for failsave interpretation
      element.name = taglist[i].substring(2); //remove identifier e-.
      if (element.name.slice(-1) == "/") { //This part manages format-elements of the form <e-name/>.
        element.name = element.name.substring(0, taglist[i].length-1);
        content.elements.push(element.clone());
      }
    } else if (taglist[i].charAt(0) == "p") { //Handles parameter tags
      parameter.name = taglist[i].substring(2); //remove identifier p-
      parameter.values = [];
    } else if (taglist[i].charAt(0) == "l") { // Handle language tags.
      var languagetag = taglist[i].substring(2); //For string analyzing, this is where the interim results are saved.
      var languagestring = ""; //Saves the language of the language tag
      if (languagetag.length >= 5) { //Filter too small tags, they are invalid.
        while (languagetag.length > 4 && languagetag.charAt(0) != "-") {//Get the language. Length check also checks for following string cuts.
          languagestring = languagestring+languagetag.charAt(0);
          languagetag = languagetag.substring(1);
        }
        value.language = languagestring;
        if (languagetag.charAt(1) == "t") { //Check for translator/content creator's approve.
          value.approved = true;
        } else {
          value.approved = false;
        }
        languagetag = languagetag.substring(4); //Cut till content is at string-beginning.
        value.text = languagetag.substring(0, languagetag.length - 1); //Strip tag ending and write to value object
        parameter.values.push(value.clone());
        variable.values.push(value.clone());
      }
    } else if (taglist[i].charAt(0) == "v") { // Handle variable tags.
      variable.name = taglist[i].substring(2); //remove identifier v-
      variable.values = [];
    } else if (taglist[i].charAt(0) == "/") { //Handle closing tags.
      if (taglist[i].charAt(1) == "e") {
        content.elements.push(element.clone());
      } else if (taglist[i].charAt(1) == "p") {
        element.parameter.push(parameter.clone());
      } else if (taglist[i].charAt(1) == "v") {
        content.variables.push(variable.clone());
      }
    }
  }
  document.write("<br>"+ JSON.stringify(content));
  return content;*/
}
?>
'use strict';
//Clone method to clone instead of just copy the reference: Call it with obj.clone();.
Object.prototype.clone = Array.prototype.clone = function()
{
    if (Object.prototype.toString.call(this) === '[object Array]')
    {
        var clone = [];
        for (var i=0; i<this.length; i++)
            clone[i] = this[i].clone();

        return clone;
    } 
    else if (typeof(this)=="object")
    {
        var clone = {};
        for (var prop in this)
            if (this.hasOwnProperty(prop))
                clone[prop] = this[prop].clone();

        return clone;
    }
    else
        return this;
}

//Input: lml-formattet string
//Output: lml data structure
function reader (lml) {
  //This part creates a list of tag-content for later analysis:
  var taglist = [];
  var current_tag_text = "";
  var bracketcounter = 0; //Counts opened brackets, if 0, tag is closed.
  for (var i = 0; i < lml.length; i++) {
    if (lml.charAt(i) == "<") {
      bracketcounter++;
    } else if (lml.charAt(i) == ">") {
      bracketcounter--;
    } else if (bracketcounter > 0) {
      current_tag_text = current_tag_text + lml.charAt(i);
    }
    //Checks if tag with content is closed:
    if (bracketcounter <= 0 && current_tag_text.length > 2) {
      taglist.push(current_tag_text);
      current_tag_text = "";
    } //Text between Tags is ignored. Later versions could also read that text to write it back for a comment-function in lml.
  }
  document.write(taglist.toString());
  //Generate datastructure from tag-list:
  var content = {elements:[], variables:[]}; //This is the content-object, which will be returned when populated with data.
  var element = {name:"", parameter:[]}; //This is the default element-object. Please only clone those objects.
  var parameter = {name:"", values:[]}; //This is the default parameter-object. Only clone.
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
      parameter.name = taglist[i].substring(2); //remove identifier p-.
    } else if (taglist[i].charAt(0) == "l") { // Handle the language tags.
      var languagetag = taglist[i].substring(2); //For string analyzing, this is where the interim results are saved.
      var languagestring = ""; //Saves the language of the language tag
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
    } //TODO: Implement variable tag v (should be equal to parameter), implement tag closing and adding data to content object.
  }
  //return datastructure;
  
}
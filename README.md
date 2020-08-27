# Language Markup Language
Changing the way content and it's translations are handled in the web.
## This problems should be adressed by LML:
* Better seperation between content and style.
    * Translators should be able to translate without knowing much about the style.
    * Content creators should be able to focus on creating content, and only define elements of style.
* Alterable Style
    * Designers should be able to change the styling of an element, change the element itself, without the need to go through all already created pages of content.
    * Moving content from one page to another in case of relaunch should be easy without keeping old design-specific tags.
## This is how it should work
LML is a new filetype only saving custom designed element-types, text, links and variables to specific content. This are the parts of LML:
* LML markup language
* Element definitions
* Interpreter
* Inter-Content-Links (automatically link keywords to web-pages).
## Implementation and roadmap
The first attempt is to provide a first version of the markup language together with an interpreter. Elments are saved at this stage in custom code (PHP, JS). After the first stage, an element language will be introduced, and the interpreter could use it. At the end, there should be an optimized workflow for web content creation, independently from used CMS for all partizipants. If this is done, in future there could be a web browser based interpretation of LML, and some optimizations in speed.
## Specification
### Element types:
* format-element: element without any content (hr, contactscript)
* style-element: contains only other elements (splitview)
* content-element: contains content data like strings and links (h1, h2, h3, text, bild, audio, embend)
* intext-element: part of the content, changes how one part of the content looks (bold, italic)
### Tags:
* \<!DOCTYPE lml 0.1> => Marks that the file is LML.
* \<e-h3> \</e> OR \<e-hr/> => Marks an element-tag.
* \<p-text> \</p> => Marks a parameter that is part of the element.
* \<l-en-t-[Hello World]> => Saves the content of the parameter for the specific language. t or f is for the translator to see if the translation is already approved.
* \<v-metatag> \</v> => Marks a variable which is not part of the content, but could be useful for the webpage, for example: meta-description, page title, keywords
### Data structure:
LML is only a standard for saving (web)-content. The larger part is the data structure behind LML. This are the object definitions for the data, that is presented after the language interpretation, it should be object oriented if possible:

| CONTENT |
|---|
| String name <br> list of elements <br> list of variables |

| VARIABLE/PARAMETER |
|---|
| String name <br> list of value |

| ELEMENT |
|---|
| String name <br> String type <br> list of parameter |

| VALUE |
|---|
| String language <br> Bool approved <br> String text |

### Elementdefinition

So sieht ein Element aus:
`PreviousHTML$variable$MiddleHTML$variable$EndHTML`


## Applications
The following applications are planned for the beginning:
* Website output: Generates webcontent from LML
* Creator tool: WYSIWYG-Editor for content creation
* Translate: Displays variables of two languages in a list for translators to work

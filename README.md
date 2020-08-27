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
## Implementation and roadmap
The first attempt is to provide a first version of the markup language together with an interpreter. Elments are saved at this stage in custom code (PHP, JS). After the first stage, an element language will be introduced, and the interpreter could use it. At the end, there should be an optimized workflow for web content creation, independently from used CMS for all partizipants. If this is done, in future there could be a web browser based interpretation of LML, and some optimizations in speed.

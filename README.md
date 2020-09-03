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
* Inter-content-links (automatically link keywords to web-pages).
* Content transport containter: definition of folders and links to transfer multimedia content 
## Implementation and roadmap
Roadmap:
* Implement interpreter
   * lml reader js - DONE
   * lml reader php - DONE
   * html output js
   * html output php
   * lml writer js
   * lml writer php
* LML specification
   * lml language definition
   * elml language definition
   * xlml language definition
* Application development
   * Website output
   * WYSIWYG editor
   * Translation tool
* LML example website
* UX-research
* Paper
* Going for standardisation and browser support
* Optimisations
## Specification
### Tags:
* \<!DOCTYPE lml 0.1> => Marks that the file is LML.
* \<e-h3> \</e> OR \<e-hr/> => Marks an element-tag.
* \<p-text> \</p> => Marks a parameter that is part of the element.
* \<l-en-t-[Hello World]> => Saves the content of the parameter for the specific language. t or f is for the translator to see if the translation is already approved. en stands for the language string. The language string could represent either a language only or a language and a region if needed. 0 stands for default.
* \<v-metatag> \</v> => Marks a variable which is not part of the content, but could be useful for the webpage, for example: meta-description, page title, keywords
### lml property definition
* Content could also be either a link to or inline LML.
* Text outside of tags is interpreted as a comment and shall not influence the output.
### File endings:
* lml: file ending for content files
* elml: file ending for element files
### Data structure:
LML is only a standard for saving (web)-content. The larger part is the data structure behind LML. This are the object definitions for the data, that is presented after the language interpretation, it should be object oriented if possible:

| CONTENT |
|---|
| list of elements <br> list of variables |

| VARIABLE/PARAMETER |
|---|
| String name <br> list of value |

| ELEMENT |
|---|
| String name <br> list of parameter |

| VALUE |
|---|
| String language <br> Bool approved <br> String text |

### Element definition

This is how elements look like:
`elementtype:PreviousHTML$type-variable$MiddleHTML$type-variable$EndHTML`

The following element types are available:
* format-element: element without any content (hr, contactscript)
* style-element: contains only other elements (splitview)
* content-element: contains content data like strings and links (h1, h2, h3, text, bild, audio, embend)
* intext-element: part of the content, changes how one part of the content looks (bold, italic)

The following variable types are available:
* text: For text-based content
* link: For references to multi-media and other content

### Linking content:
All internal links (other web content, media) part of the link variable type are given relative to a virtually defined root directory. This directory is defined with a prefix given to the renderer. All external links are given with full URL.

### Interpreter specification
Interpreter consists of multiple parts:
#### LML reader
Input: lml
Output: data structure
#### HTML renderer
Input: data structure, elml, virtual root dir, language 
Output: html
#### LML writer
Input: data structure
Output:lml

## Applications
The following applications are planned for the beginning:
* Website output: Generates webcontent from LML <br> Structure: lml reader => html renderer
* Creator tool: WYSIWYG-Editor for content creation
* Translate: Displays variables of two languages in a list for translators to work <br> Structure: lml reader => lml writer

# Developer

Sebastian Unger - planetcat.de

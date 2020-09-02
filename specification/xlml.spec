Specification Exchange-LML (xlml):
Description: xlml is a format for transport, enriching translations and backup of multimedia content.
Requirements:
  1. xlml should contain all data to restore content on a target system.
  2. xlml should be trasported as one file.
  3. xlml should be based on the lml standard.
  4. xlml should contain element descriptions.
  5. xlml should be able to inform about content's copyrights.
Definitions:
  - xlml files are based on the zip-standard, but with xlml ending.
  - zip folder structure looks like this:
    root-dir
    ↪️ .lml file with text content
    ↪️ folder structure with content (img, vid, etc.)
    ↪️ .readme file for right declaration
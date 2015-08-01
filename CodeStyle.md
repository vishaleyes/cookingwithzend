# Guidelines #

At time of writing I am sure that not all the files adhere to these rules but I will try to make any new files added to to the project and any current files convert over to the following guidelines

## PHP Guidelines ##

  * Use unix friendly line endings, if I find ^M's everywhere I'll brain you :)
  * Indents are tabs not spaces
  * Indents are set to 4 (for my editor)
  * Brackets on the next line, not same line
  * Never EVER store a whole object in a session, just don't, trust me :)
  * Use standard PHPdoc formatting for commenting functions e.g.

```
/**
 * Text to decribe stuff here
 *
 * @param $var Type Description
 * @return
 */
```

## HTML Guidelines ##

  * HTML id attributes should use hyphens
  * HTML name attributes should use undescores
  * NEVER have an id attribute called the same as a name attribute
  * Use double quotes in HTML for attributes
# Editing CSS

It is possible to edit the CSS directly, but this will make it impossible for future developers to build on top of your work, and presumably it will be harder than actually compiling SCSS. You will need to setup a SASS compilation workflow. As a starting point, please refer to the "developers-workflow" document here.

 * More about SASS: http://sass-lang.com/

## How to know what to edit?

Provided that the last developer compiled the SCSS using source map, you can easily know what source file to edit in order to make a specific change.
* Visit the website online
* Inspect the element that you want to edit (right click>inspect)
* Find the property that needs changing. There should be an indication of what SCSS source file defined that property.

The PHP files define as little as possible of the styling, making it possible to change all the styles by changing only the CSS. The PHP files, however, define the order of appearance of each element.

There is a consistent structure throughout the pages that allow applying the same rules in different cases, and prevents these rules to be applied accidentally. The names of the containers usually contain one class name that indicates the generic role of that container within the page, as well as a name that is specific only in that context. In addition to this, the main container has classes that indicate the template in use, and the page name. In this way it is possible to make rules with different levels of specificity.

For example, to apply a rule to any items-container, you would just use the `.items-container` selector. If you were to apply rules to the same container, but only in a single page template, you'd use the `body.single .items-container`. If you needed to apply a style only to a specific post, for instance, you could use the `.main-specific-post-slug-container .items-container`. If you'd like, for instance to target any pilots related items container, anywhere in the site, you'd use the `.items-pilots-container`. 

```
┌───────────────────────────────────────────┐   
│   HTML                                    │   
│                                           │   
│ ░░░░░░░░░.main-container ░░░░░░░░░░░░░░░░ │   --> header.php
│ ░░┌────────────────────────────────────┐░ │   
│ ░░│  .section-container                │░ │
│ ░░│  .section-<name>-container         │░ │
│ ░░│ ┌────────────────────────────────┐ │░ │   
│ ░░│ │  .item-container               │ │░ │   
│ ░░│ │  .item-<name>-container        │ │░ │   (example of a single-content
│ ░░│ │  ..depending on the content    │ │░ │   section)
│ ░░│ │  type, different classes may   │ │░ │
│ ░░│ │   be present.                  │ │░ │   
│ ░░│ └────────────────────────────────┘ │░ │   
│ ░░└────────────────────────────────────┘░ │   
│ ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ │  
│ ░░┌────────────────────────────────────┐░ │    
│ ░░│  .section-container                │░ │
│ ░░│  .section-<name>-container         │░ │
│ ░░│ ┌────────────────────────────────┐ │░ │   
│ ░░│ │  .items-wrapper                │ │░ │
│ ░░│ │  .items-<name>-wrapper         │ │░ │   (example of a section
│ ░░│ │  ┌───────┐ ┌───────┐ ┌───────┐ │ │░ │   containing a list of
│ ░░│ │  │.item- │ │.item- │ │.item- │ │ │░ │   elements, such as pilots)
│ ░░│ │  │<name>-│ │<name>-│ │<name>-│ │ │░ │   
│ ░░│ │  │cont.. │ │cont.. │ │cont.. │ │ │░ │   
│ ░░│ │  └───────┘ └───────┘ └───────┘ │ │░ │   
│ ░░│ └────────────────────────────────┘ │░ │   
│ ░░└────────────────────────────────────┘░ │   
│ ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ │   
│ ░░┌────────────────────────────────────┐░ │   --> footer.php  
│ ░░│  .section-container                │░ │   
│ ░░│  .section-footer-container         │░ │   
│ ░░│ ┌────────────────────────────────┐ │░ │   
│ ░░│ │  item-footer-container         │ │░ │     
│ ░░│ └────────────────────────────────┘ │░ │   
│ ░░└────────────────────────────────────┘░ │   
│ ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ │
│                                           │   
└───────────────────────────────────────────┘   
```

## SCSS files
### Folders:

      ./SRC
        └─── scss
              ├───base
              ├───components
              │     ├───postlists
              │     └───single
              └───plugins
                    ├───bootstrap
                    │    └───mixins
                    └───font-awesome

#### Base

Files with the basic CSS setup. Changing things here takes effect across all the pages.

Note: Some changes could be shadowed by settings at `./src/scss/plugins/bootstrap`


#### Components

The SCSS defines behaviours that are exclusive for this theme, reducing the changes done to the imported SCSS present in other folders. This is the most likely place where to tweak details on the appearance of the theme.

* `components/` folder
  * **snippets:**
      contains mixins for the use within this `components/` folder.
  * **mixin-default-post-item:**
      The contents of this file might've as well been inside the snippets file. Contains the mixin that is used in the default post item, and in a single event item when they are being displayed as a list.
  * **layout:**
      Defines some laying-out rules for containers that are present in every page, which only make sense in context to this theme.
  * **scaffolding:**
      Contains default rules for html elements within the theme.
  * **section-specific:**
      This file contains the properties of very specific items that appear in some different pages
  * **section-main-menu:**
      Specifies the behaviour of the main menu. Note that the main menu has many states such as collapsed and inactive, collapsed active, sticky-positioned, with or without sub-menus, etc.
  * **available:**
      This file specifies classes that are made available to the content editors.
  * **`single/` folder:**
    Definitions that are specific to theme templates for single items.
    * **page:**
      Page specific rules for the pages which contain a list of items of a certain post type and a static page. In other words, the "news", "events", "pilots","home", "jobs", "about".
    * **page-events:**
      Rules that are specific to a single event page.
    * **post_or_custom:**
      Rules that are specific to a single post that doesn't have a specific template.
    * **pilot:**
      Rules that are specific to a single pilot page.
    * **event:**
      Rules that are specific to a single event page.
    * **page-home:**
      Rules that are specific to the home page.
    * **page-about:**
      Rules that are specific to the about page.
    * **post:**
      Rules that are used in any single post or page.
  * **`postlists/` folder:**
    Files in the Postlist folder define rules for single items (e.g. post, events) when they appear in a list (contained in a `.items-wrapper`)
    * **common:**
      Rules that are applied to any `.item-container`
    * **team_members:**
      Rules for team-member items.
    * **pilots:**
      Rules for pilot items.
    * **events:**
      Rules for event items.
    * **news:**
      Rules for news items.

#### Plugins

Contains external plugins, such as Bootstrap. This makes bootstrap, hence, available to content editors.

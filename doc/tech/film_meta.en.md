---
title: Metadata structures for films and film images
backlink: ../about
backlink-title: Dokumentation / Documentation
robots: noindex
---

# Metadata structures for films and film images

The primary purpose of the data structures described below is to allow for
publication of selected articles - technically sections of film images -, after
their intellectual property status had be checked.

## Films

* File `checked.yaml` in a film directory lists sections of the film which can published, because every image had been checked for intellectual property issues
  * contains (possibly multiple) sections, per section:
  * `title_en`, `title_de`: short title for the section (optional, free format)
  * `start`, `end`: first and last image checked for copyright issues (mandatory)
  * `checked_by`, `checked_date`: who has done the checking, and when (mandatory)
  * [example](../../film/h1/sh/S0292H/checked.yaml)
* for each article with a named text and/or image author, create lock and/or meta files for each page

## Film images

* File `{image_name}.locked.txt` indicates that the image contains an article with a named text or image author, who is either not identified, whose death date is unknown, or whose death was less than 70 years ago. _Each page (image) with such an article has to be locked._ The file is created manually, it cannot be replaced automatically.
* File `{image_name}.meta.yaml` contains metadata fields about the image
  * `date`: publication date
  * `author`: in case of an identified author, QID of the author's Wikidata item. If the item has a death date, that could be evaluated in order to remove the lock file when the IPR has expired
  * `title`: title of the article
  * `publication`: in case of an identified publication QID of the publication's Wikidata item
  * `pos`: position on the page L=left, R=right
  * [example 1](../../film/h1/sh/S0292H/S02920681H.meta.yaml) ([image](../../film/h1/sh/S0292H/0681)), [example 2 (locked)](../../film/h1/sh/S0449H/S04491187H.meta.yaml) ([image](../../film/h1/sh/S0449H/1187))
  * the file may describe more than one article, and one article may stretch across multiple pages
  * for meta files of follow-up pages of an article, symlinks may be used
  * see also structure of [annotations for _articles_](annotation)

## Input files and worksflow

### Small sections (working ad-hoc workflow)

* Create or edit a `checked.yaml` the film directory
```
    cd {film_directory}
    cp ../../../checked.yaml .
```
* Fill in `checked.yaml` (set title for the section, identify first and last image, etc.)
* [Check images for intellectual property rights](../ipr) and create a lock file for every dubious image file
```
    touch {image_name}.locked.txt
```
* Optionally create `{image_name}.meta.yaml` files
```
    cp ../../meta.yaml {image_name}.meta.yaml
```
* Execute `bin/public_film_sections.sh`

### Massive amounts (draft)

* Files
  * per film, create a `checked_yaml` (from csv or yaml template), with fields film_id, section_no, title_de, title_en, checked_by, checked_date
  * create a csv file with, for each image of a film_id, image_id (as link), is_locked (defaults true), pos, author, date, publication, title
* Workflow
  * user sets the fields for checked.yaml 
  * in csv, user fills in section number(s) and optionally removes all lines not needed for sections (optional)
  * user checks the rights status for every image, removes is_locked
  * optional: user fills in author and other fields (make pos required in that case)
* Verification
  * valid and matching film_ids
  * matching and ascending section numbers
  * pos set for locked and annotated images
* Alternatively, set annotations completely with an external software plus custom script.


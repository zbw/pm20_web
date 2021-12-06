---
title: Metadata structures for documents
etr: doc
backlink: ../about
backlink-title: Dokumentation / Documentation
---

# Metadata structures for documents

_Should apply to newspaper articles, annual reports and other types of documents!_


## Files

The file __`meta.yaml`__ is located in the document directory within the folder
tree.

Sources of document metadata:

- image file name conventions
- `DocAttribute_?.txt` files
- intellectually created files
- perhaps: Hypothes.is annotations

TODO how to keep track of the sources?

## Fields

TODO structure for multiple authors?

:::: {.wikitable}

Field name|Description
-|----
date|publication date of the document
title|title of the document
author_id|IFIS ID of an identified author
author_qid|Wikidata QID of an identified author
author|name - as given in the document - of an unidentified author
death_year|death year of an identified author - __used for IPR status computation!__
death_src|source for the death year (e.g., 'wd')
pub_id|IFIS ID of an identified publication
pub_qid|Wikidata QID of an identified publication
pub|name - as given in the document - of an unidetified publication
pub_type|identifier for the publication type (TODO source? mapping table?)
description|description for the document
comment|note for internal use

::::

TODO how to handle derived information (such as `author_name` derived from ID)


## Special cases

### Author is folder subject person (in the persons archive)

Create a file named `folder_person_meta.yaml` with fields `author_qid`,
`death_year`, and `death_src`, and create `meta.yaml` symlinks to it in the
document directories.

If additional, document-specific fields are required, copy and edit the
original file.


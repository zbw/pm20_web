# Makefile
# nbt, 14.11.2019

# Convert markdown files in the web diretory recursively to html
#
# has to be called with
#   "make"              (all = default set of pages, w/o folders or categories)
#   "make SET={param}"  (for params see mk/find_md.sh),
# e.g.
# 		SET=pe
# 		SET=sh/140892,144504  # for testing with specific folders
# 		SET=category

# The SET={param} allows to split the large total set of files into manageable
# subsets. It is implemented by invocation of an external bash script with
# tailored find commands, which for large trees is much faster than plain make.

# requires: make, find, pandoc


# binaries
PANDOC=/usr/bin/pandoc-bin

# auxiliary
EMPTY :=

# input/output

# create the list of files to process via external script with find command
SOURCE_DOCS := $(shell mk/find_md.sh $(SET))

# split into two sets for standalone html files and html fragments (the latter
# are generated in the film directory in order to include a navigation section
# into the filmviewer.php output)
SOURCE_DOCS := $(filter-out templates/fragments/*.md, $(SOURCE_DOCS))
EXPORTED_DOCS := $(SOURCE_DOCS:.md=.html)
SOURCE_FRAG := $(wildcard templates/fragments/*.md.frag)
EXPORTED_FRAG =  $(SOURCE_FRAG:.md.frag=.html.frag)


# options for pandoc invocation
TEMPLATE    := /pm20/web/templates/pm20_default.html
PANDOC_OPTS	:= --standalone
TMPL_OPTS		:= --template $(TEMPLATE) --css /styles/simple.css
EXT_OPTS		:= -f markdown+pipe_tables+fenced_divs+bracketed_spans -t html

# prepare some filename and directory information for use in the template
# via "-- variable"

# extract language from filename, e.g. about.de.md
lang 			= $(subst .,$(EMPTY),$(suffix $(basename $<)))
lang_opts = --variable is_$(lang) --variable lang:$(lang)

# path (used in the template for navigational links)
path_opts = --variable targetdir:$(@D)


# input file basename (strip dir portion and both extensions)
bsname	= $(basename $(basename $(notdir $<)))


# Pattern-matching Rules
set: $(EXPORTED_FRAG) $(EXPORTED_DOCS)

# include modules (currently only for debugging)
include $(wildcard mk/*.mk)

# standalone HTML pages
%.html: %.md $(source_frag) $(TEMPLATE)
	@echo $@
	$(PANDOC) $(PANDOC_OPTS) $(lang_opts) $(path_opts) $(TMPL_OPTS) $(EXT_OPTS) -o $@ $<

# HTML fragments (for inclusion)
%.html.frag: %.md.frag
	@echo $@
	@$(PANDOC) $(EXT_OPTS) -o $@ $<

##show_all_files:
##	@echo All document .md files: $(SOURCE_DOCS)
##	@echo All fragment .md files: $(SOURCE_FRAG)


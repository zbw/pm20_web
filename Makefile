# Makefile
# nbt, 14.11.2019

# Convert all markdown files in the web diretory recursively to
# html
#
# has to be called with
#   "make"              (all = default set of pages, w/o folders)
#   "make SET={param}"  (for params see mk/find_md.sh),
# e.g.
# 		SET=pe
# 		SET=sh/140892,144504
# 		SET=category

# requires: make, pandoc


# binaries
PANDOC=/usr/bin/pandoc-bin

# auxiliary
EMPTY :=

# input/output
SOURCE_DOCS := $(shell mk/find_md.sh $(SET))
SOURCE_DOCS := $(filter-out templates/fragments/*.md, $(SOURCE_DOCS))
EXPORTED_DOCS := $(SOURCE_DOCS:.md=.html)
SOURCE_FRAG := $(wildcard templates/fragments/*.md.frag)
EXPORTED_FRAG =  $(SOURCE_FRAG:.md.frag=.html.frag)

# options for pandoc invocation
TEMPLATE    := /pm20/web/templates/pm20_default.html
PANDOC_OPTS	:= --standalone
TMPL_OPTS		:= --template $(TEMPLATE) --css /styles/simple.css
EXT_OPTS		:= -f markdown+pipe_tables+fenced_divs+bracketed_spans -t html

# extract language from filename, e.g. about.de.md
lang 			= $(subst .,$(EMPTY),$(suffix $(basename $<)))
lang_opts = --variable is_$(lang) --variable lang:$(lang)

# path
path_opts = --variable targetdir:$(@D)

# input file basename (strip dir portion and both extensions)
bsname	= $(basename $(basename $(notdir $<)))


# Pattern-matching Rules
set: $(EXPORTED_FRAG) $(EXPORTED_DOCS)

# include modules
include $(wildcard mk/*.mk)

# standalone HTML pages
# treat index pages (about.(de|en).md) specially, in suppressing them in
# template output
%.html: %.md $(source_frag) $(TEMPLATE)
	@echo $@
	@if [ "$(bsname)" = "about" ]; then export about_opt="--variable is_about:1"; fi ;\
	$(PANDOC) $(PANDOC_OPTS) $(lang_opts) $(path_opts) $$about_opt $(TMPL_OPTS) $(EXT_OPTS) -o $@ $<

# HTML fragments (for inclusion)
%.html.frag: %.md.frag
	@echo $@
	@$(PANDOC) $(EXT_OPTS) -o $@ $<

##show_all_files:
##	@echo All document .md files: $(SOURCE_DOCS)
##	@echo All fragment .md files: $(SOURCE_FRAG)


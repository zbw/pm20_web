# Makefile
# nbt, 14.11.2019

# Convert all markdown files in the web.(public|intern) diretory recursively to
# html
#
# has to be called with SET=param (for params see mk/find_md.sh)

# requires: make pandoc


# include modules
include $(wildcard mk/*.mk)

# binaries
PANDOC=/usr/local/bin/pandoc

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
EXT_OPTS		:= -t html+pipe_tables+fenced_divs+bracketed_spans

# extract language from filename, e.g. about.de.md
lang 			= $(subst .,$(EMPTY),$(suffix $(basename $<)))
lang_opts = --variable is_$(lang) --variable lang:$(lang)


# Pattern-matching Rules

all: $(EXPORTED_FRAG) $(EXPORTED_DOCS)

# standalone HTML pages
%.html: %.md $(SOURCE_FRAG) $(TEMPLATE)
	@echo $@
	@$(PANDOC) $(PANDOC_OPTS) $(lang_opts) $(TMPL_OPTS) $(EXT_OPTS) -o $@ $<

# HTML fragments (for inclusion)
%.html.frag: %.md.frag
	@echo $@
	@$(PANDOC) $(EXT_OPTS) -o $@ $<

##show_all_files:
##	@echo All document .md files: $(SOURCE_DOCS)
##	@echo All fragment .md files: $(SOURCE_FRAG)


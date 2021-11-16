.PHONY:	debug
debug: ## display local make variables defined
	@$(foreach V, $(sort $(.VARIABLES)), \
		$(if $(filter-out environment% default automatic,\
			$(origin $V)), \
			$(warning $V = $($V) )) \
	)

.PHONY:	debug-all
debug-all: ## display all make variables defined
	@$(foreach V, $(sort $(.VARIABLES)), \
		$(warning $V = $($V) ) \
	)


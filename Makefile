.PHONY: encore cc db-reset bin

CONSOLE=bin/console

encore:
	yarn run encore dev --watch

cc:
	$(CONSOLE) c:c --env=dev

db-create:
	$(CONSOLE) d:d:c --if-not-exists

db-drop:
	$(CONSOLE) d:s:d --force

db-update:
	$(CONSOLE) d:s:u --force

fixtures:
	$(CONSOLE) d:f:l --no-interaction

controller:
	$(CONSOLE) make:controller

form:
	$(CONSOLE) make:form

entity:
	$(CONSOLE) make:entity

db-reset:
	make db-drop
	make db-create
	make db-update
	make fixtures

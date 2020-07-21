help:                                                                           ## shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init:                                                                           ## initialize project
	composer install

phpunit:                                                                        ## run phpunit tests
	vendor/bin/phpunit --testdox --colors=always -v $(OPTIONS)

coverage:                                                                       ## run phpunit tests with coverage
	xdebug vendor/bin/phpunit --testdox -v --coverage-html coverage/ $(OPTIONS)

phpstan:                                                                        ## run phpstan static code analyser
	phpstan analyse -l max src

psalm:                                                                          ## run psalm static code analyser
	psalm $(OPTIONS) --show-info=false

psalm-info:                                                                     ## run psalm static code analyser with info
	psalm $(OPTIONS)

php-cs-check:																	## run cs fixer (dry-run)
	PHP_CS_FIXER_FUTURE_MODE=1 php-cs-fixer fix --allow-risky=yes --diff --dry-run

php-cs-fix:																		## run cs fixer
	PHP_CS_FIXER_FUTURE_MODE=1 php-cs-fixer fix --allow-risky=yes

test: phpunit                                                                   ## run tests

static: php-cs-fix psalm phpstan                                                ## run static analysers

dev: static test                                                                ## run dev checks

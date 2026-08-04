# Changelog

All notable changes to `laravel-url-rewrites` will be documented in this file.

## [dev-master] - 2026-08-04

### Fixed
- De catch-all rewrite-route (`Route::rewrites()`) matcht een lege `{url}` niet meer. De constraint eist nu minstens een teken (`.+` i.p.v. `.*`, en de Nova-variant `^(?!nova).+`), zodat een request dat naar een leeg pad decodeert (bijv. `/%2f` van scan-/probe-verkeer) al op routing-niveau doorvalt naar een 404. Aanvullende hardening bovenop de 404-fallback in `UrlRewriteController::__invoke()`.

## [dev-master] - 2026-08-03

### Fixed
- `UrlRewriteController::__invoke()` geeft nu een 404 in plaats van een 500 wanneer de `{url}`-parameter leeg of afwezig is. Een request dat naar een leeg pad decodeert (bijv. `/%2f` van geautomatiseerd scan-/probe-verkeer) liet Laravel de lege parameter wegfilteren, waardoor de controller zonder argument werd aangeroepen en een `ArgumentCountError` (500) gooide. De parameter heeft nu een default en lege/ontbrekende waarden vallen netjes terug op `abort(404)`.

## [dev-master] - 2026-07-13

### Fixed
- `UrlRewriteController::forwardResponse()` encodeert nu padsegmenten met `rawurlencode` en trimt de resulterende URL. Dit voorkomt 500-fouten bij `target_path` waarden met spaties, komma's of trailing whitespace (bijv. AFAS-categorieën zoals "PS 4,8") die door Symfony's strikte `Request::create()`-validatie werden geblokkeerd.

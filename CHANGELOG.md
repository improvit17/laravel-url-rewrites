# Changelog

All notable changes to `laravel-url-rewrites` will be documented in this file.

## [dev-master] - 2026-07-13

### Fixed
- `UrlRewriteController::forwardResponse()` encodeert nu padsegmenten met `rawurlencode` en trimt de resulterende URL. Dit voorkomt 500-fouten bij `target_path` waarden met spaties, komma's of trailing whitespace (bijv. AFAS-categorieën zoals "PS 4,8") die door Symfony's strikte `Request::create()`-validatie werden geblokkeerd.

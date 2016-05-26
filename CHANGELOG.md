# Change Log

This file keeps track of all changes to this project. This project follows [semantic versioning](http://semver.org/) and [keeps a change log](http://keepachangelog.com/).


## [UNRELEASED]

No changes yet.


## v0.0.3 - 2016-05-26

### Added
- Add `--reportUndefined` option to CLI to report architectural relations not covered by a policy
- Distinguish between minor and major severities for architecture violations
- Improved archinspec console application messages and input validation

### Changed
- Moved default location for architecture definition files to .qa/architecture.yml

### Fixed
- Failed to load empty architecture definition file


## v0.0.2 - 2016-05-11

### Fixed
- Add run script to easily use archinspec after installation with composer


## v0.0.1 - 2016-05-11

### Added
- Architecture dependency definition language
- Symfony console application to run archinspec
- Report architecture violations
# Changelog
All notable changes to this project will be documented in this file.

## [5.0.4] - 2018-01-03
### Added
- Fade effect plus easing and speed args

### Changed
- unset body styles on unlock instead of setting to 0 margin and auto

## [5.0.3] - 2018-01-03
### Changed
- Swap aria hidden attribute on show hide, dont remove it when showing.
- On body unlock remove bod class last

## [5.0.2] - 2017-12-17
### Added
- Added append target to args

## [5.0.1] - 2017-12-17
### Added
- New render event

## [5.0.0] - 2017-12-17
### Added
- New features to auto inject dialog wrapper
- New render event
- Options object when constructing
- Ability to lock the body at its scroll position when the dialog is opened

### Changed
- Rewrote large portion of code
- Render dialog only on first request
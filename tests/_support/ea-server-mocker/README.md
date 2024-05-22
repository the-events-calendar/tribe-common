# Event Aggregator Server Mocker

## Installation
Symlink the plugin folder into the WordPress plugins folder, **do not copy it**.
WordPress will resolve symbolically linked plugins perfectly so do not worry: no extra care needed.

On *nix and Mac from the plugins root folder:

```shell
ln -s the-events-calendar/common/tests/_support/ea-server-mocker .
```

## Usage
- Activate the plugin from the plugins administration screen
- Find relevant options in the "Tools -> EA Mocker" options page
- Please read the hints in the fields
- Set the options

Keep in mind this is a testing tool **meant for internal use**; do not redistribute.

## Contributing
You are welcome to, but please take care to:

* see what other code is already there and follow the spirit
* rather than adding code to a class extend it and bind that on top of the extended one using the container; this will ensure we can later register and use different binding configurations according to TEC/EA versions. I'm not insulting your development skills attaching an example (but "Open-Closed Principle")\
* Adding fields? Add hints.
* keep this PHP 5.2 compatible

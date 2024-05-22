## Testing with real .mo files

To test with real `.mo` localization files, you will need to generate them from
the `.po` files; in turn, the `.po` files will need to be generated from the `.pot` files.

### Generating .pot files from this plugin
This plugins, the `Test Text Domain` plugin, has only one function that will do nothing that will
call the WordPress built-in localization function `__()`.
If you need to have more localized strings in your test `.mo` file, you can add more calls to `__()`
in the plugin.

Use wp-cli to generate the `.pot` file from the plugin (assuming you are in the plugin root directory):

    $ wp i18n make-pot . test-text-domain.pot

Now generate the `.po` file for the desired locale (`it_IT` in the example) from the `.pot` file:

    $ touch test-text-domain-it_IT.po
    $ wp i18n update-po ./test-text-domain.pot test-text-domain-it_IT.po

Update the `.po` file to contain the translations you require.

Now generate the `.mo` file from the `.po` file:

    $ wp i18n make-mo ./text-text-domain-it_IT.po

You will end up with a `test-text-domain-it_IT.mo` file in the plugin root directory ready to be used in translations.

> Avoid putting too much stuff in here and keep in mind this is a test plugin!

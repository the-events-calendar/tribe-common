# tribe-common
Common classes and functions used in our plugins

## Specs and planning

Work on this repository is planned before it is written, using
[OpenSpec](https://github.com/Fission-AI/OpenSpec). **This is enforced** — every
pull request is checked for a plan.

Plans do not live here. A TEC feature routinely spans several repositories, so a
spec kept in one of them is invisible from the others. They all live in one shared
store instead: [`the-events-calendar/plans`](https://github.com/the-events-calendar/plans).

### First time only

```bash
npm install -g @fission-ai/openspec
git clone git@github.com:the-events-calendar/plans.git ~/repos/tec-plans
openspec store register ~/repos/tec-plans --id tec-plans
```

The clone path is yours to choose. The `--id` is not — every command refers to the
store as `tec-plans`.

### Working on a ticket

1. **Create the change before writing code**, named after the ticket in lower
   case. OpenSpec requires kebab-case and rejects anything else, so the ticket
   `SOFT-1234` becomes the change `soft-1234`:
   `openspec new change soft-1234 --store tec-plans --description "what this does"`
2. Write the proposal, then commit and push it in the plans repo.
3. Branch as usual: `feat/SOFT-1234/short-desc`.
4. Implement. If the work shows the plan was wrong — it often does — update the
   change rather than letting the plan and the code drift apart.
5. Open the PR. The template asks for the change ID, and CI checks the plan exists
   and is still active.

Commands that read or write plans take `--store tec-plans`: `new change`,
`status`, `instructions`, `list`, `show`, `validate`, `archive`, `context` and
`doctor`. The `openspec store` commands manage registrations instead and take
`--id`, which is why the setup block above uses that.

Nothing sets a default store for you — the skills repo's `install.sh` is
org-wide and does not know you work on TEC. Without the flag the command writes
the change into whatever repository you happen to be standing in. A developer who
only works on TEC may run `openspec config set defaultStore tec-plans` once as a
personal convenience; the flag stays in every example because the next machine
will not have it.

### Where the rest is written down

This section covers what is specific to working here. The
[`openspec-workflow` skill](https://github.com/stellarwp/skills-se) covers the
workflow itself — writing a proposal worth reviewing and keeping it current — and
its TEC extension, `tec-openspec`, covers the shared store, the ticket-ID naming,
and archiving once (after the last repository merges, not per repo). Install it with:

The below will work only once the `stellarwp/skills-se` becomes public.

```text
/plugin marketplace add stellarwp/skills-se
/plugin install nexcess-se
```

## Running the tests

PHP tests are Codeception + wp-browser suites, run in Docker by [slic](https://github.com/stellarwp/slic).

`tribe-common` is never installed standalone — it ships as the `common/` directory inside The
Events Calendar and Event Tickets, and its suites load TEC as an active plugin. The tests
therefore run from *inside a TEC checkout*, which is what CI builds: it clones
`the-events-calendar`, deletes its `common/`, copies this repo in its place, and points slic at
`the-events-calendar/common`.

### Setup

Run everything from a parent directory holding slic and the plugins side by side (e.g.
`~/repos/`), matching CI's layout.

```bash
git clone git@github.com:stellarwp/slic.git
git clone git@github.com:the-events-calendar/the-events-calendar.git --recursive

cd ~/repos
./slic/slic here
./slic/slic interactive off && ./slic/slic build-prompt off
./slic/slic build-subdir off && ./slic/slic xdebug off

# TEC dependencies
./slic/slic use the-events-calendar
./slic/slic composer install --no-dev

# Your tribe-common working copy must live at the-events-calendar/common. TEC tracks common as
# a submodule, so the simplest option is to work directly in the-events-calendar/common. To test
# a checkout kept elsewhere, mirror CI:
#   This DELETES the-events-calendar/common, including anything uncommitted or
#   untracked in it. Commit or stash there first, and check it is clean:
#     git -C the-events-calendar/common status --porcelain   # expect no output
#   Then: rm -rf the-events-calendar/common && cp -r tribe-common the-events-calendar/common

# Common dependencies (dev included)
./slic/slic use the-events-calendar/common
./slic/slic composer install

# WordPress + theme
./slic/slic up wordpress
./slic/slic wp core update-db
./slic/slic wp theme install twentytwenty --activate
```

No `.env` work needed: `.env.testing.slic` is committed and `codeception.slic.yml` reads it.

### Running a suite

```bash
./slic/slic use the-events-calendar/common
./slic/slic run wpunit

# one file / one method / a filter
./slic/slic run wpunit tests/wpunit/Tribe/CacheTest.php
./slic/slic run wpunit tests/wpunit/Tribe/CacheTest.php:should_expire_cache_on_each_trigger
./slic/slic run wpunit --filter array_access
```

### Suites

| Suite | Covers | CI |
|---|---|---|
| `unit` | Pure unit tests, no WordPress loaded | every PR |
| `wpunit` | Bulk of coverage: WP loaded via WPLoader, TEC active, WPQueries assertions | every PR |
| `muwpunit` | Same as `wpunit`, on multisite | every PR |
| `integration` | Integration tests against a loaded WP + TEC | every PR |
| `activation` | Plugin activation/deactivation through a real browser + DB | every PR |
| `dependency` | The plugin dependency manager (version gating, notices) | every PR |
| `snapshots` | Template/markup snapshot assertions | every PR |
| `rest_tec_v1_integration` | TEC REST API v1 controllers and the OpenAPI document | every PR (+ Spectral lint) |
| `end2end` | Browser-driven end-to-end tests (WPWebDriver/chromedriver) | no |
| `eva_integration` | Cross-plugin tests needing ECP, ET, ET+ and WooCommerce | no |
| `restv1`, `restv1_et` | Legacy REST v1 suites, driven by SQL dumps | no |

CI skips the whole test job when a PR changes no PHP files.

### How this differs from CI

- CI pins WordPress with `./slic/slic wp core update --force --version=6.8` (the minimum supported);
  locally, use whatever slic ships unless chasing a version-specific failure.
- CI picks the TEC branch by smart-checkout fallback (same-name branch → PR base → default).
  Locally, check out whichever TEC branch your change needs.
- After `rest_tec_v1_integration`, CI also runs `npm ci` (Node 18.17.0 from `.nvmrc`) and
  `npm run spectral -- http://localhost:8888/wp-json/tec/v1/docs/`. Reproducible locally after
  `./slic/slic wp plugin activate the-events-calendar` and
  `./slic/slic wp rewrite structure '/%postname%/' --hard`, but not needed for normal PHP work.

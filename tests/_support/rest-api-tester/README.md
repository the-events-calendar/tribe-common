# The Events Calendar REST API test plugin.

## Installation
1. Copy the plugin from its current location (`the-events-calendar/common/tests/_support`) to the WordPress plugins folder.  
2. Make sure The Events Calendar is activated
3. Activate the plugin as any other plugin, it's called "TEC REST API Tester"

## Usage
The plugin lives entirely in its only option page.  
From there you will be provided a tab for each endpoint we support.  
To make a request:

1. choose an endpoing (a tab)
2. choose the request method
3. choose the user that should make the request; you will be presented with all the users currently available in the WordPress installation; each user can see/manipulate different content in relation to their role.
4. optionally fill in a request field
5. make the request
6. watch the result

## Caveats
This is a testing tool: it is **not** a full blown REST API client, and it is **not** an easy-to-use tool.  
You will incur in bad requests and (maybe) internal errors **by design**.  
You are being provided the same documentation we provide to our users.  
REST APIs are developer tools and the objective of this plugin is to poke hole in ours.

## Technologies
The technology structure you are dealing with is the following:

* TEC REST API is built on top of...
* ...the WordPress REST API **infrastructure** baked into WordPress 4.4

What about WordPress REST API (not just the infrastructure)?

* WordPress REST API is built on top of...
* ...the WordPress REST API **infrastructure** baked into WordPress 4.4

Same infrastructure, different APIs with similar behaviours.

## Good readings
The [REST API handbook](https://developer.wordpress.org/rest-api/) is a good starting point.  

## Development requirements
You will need [Browserify](https://developer.wordpress.org/rest-api/) and [Typescript](https://www.typescriptlang.org/) to work and recompile the JS code.


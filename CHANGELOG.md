# Changelog

## v1.7

[Full List of core changes since 1.6.1](https://github.com/fuel/core/compare/1.6/master...1.7/master)

### Important fixes, changes, notes. Read them carefully.

* A fix has been added to deal with PHP bugs #42098/#54054, which cause an SPL autoloader to malfunction when trying to autoload from an exception handler. This will fix erradic "class not found" messages when processing exceptions.
* __File::close_file()__ was broken when using locking. This has been fixed.
* __Date::test_format()__ no longer resets the current timezone to UTC.
* Output buffering is now disabled when in CLI mode. This allows you to get messages from your tasks in realtime, instead of having to wait until the task has finished.
* A bug in all session drivers (except cookie) that caused the session timestamp not to be updated has been fixed. The session will now not expire as long as there is activity within the expiration timeout.
* PHP E_ERROR's are now reported as "Fatal error".
* Profiler data will no longer be added to the output if the request is an ajax call.
* The finder now checks for "?:\" to detect a Windows path, so that one-letter module names can be used in finder filenames ("?:filename").
* Where relevant calls to `file_exists()` have been changed to `is_file()` for performance reasons.
* Where relevant calls to `call_user_func_array()` have been changed to `call_fuel_func_array()`, our internal equivalent which is about 30% faster.
* Lots of bugfixes in the __Auth__ package, especially in relation to OpAuth and the Ormauth drivers.
* Lots of bugfixes in the __Orm__ package, especially related to Model_Soft and Model_Nestedset.

### Backward compatibility notes

* The CSV configuration for the Format class has been split into a separate config for imports and exports. Also, the default escape character has been changed from a backslash to a double quote, to be more standards compliant. If your application relies on the backslash, make sure to create a custom format config after you have upgraded.
* The __Redis__ class has been renamed to __Redis_Db__, to avoid collisions with the __Redis__ PECL class, which seems to be installed by default on a lot of systems. If the PECL extension is not found, __Redis_Db__ will be aliased to __Redis__, to make sure existing applications that use the __Redis__ class don't break. If you use the __Redis__ class, it is advised that you change it to __Redis_Db__.
* __Cookie::set()__ now returns `false` when called in CLI mode.

### Removed code (because it was deprecated in v1.6 or earlier)

* The __Event::shutdown()__ method has been removed. This is replaced by two events, 'shutdown' which can be used by applications to run code after the script has finished, and 'fuel-shutdown', which runs after the application shutdown events, will close any open session, and runs the framework cleanup.

### Security related

There were two security advisories issued for 1.6.1, which also impact all previous versions (see http://fuelphp.com/security-advisories). These issues are addressed in 1.7, it is strongly advised that you upgrade as soon as possible.

### System changes

* Composer now runs "oil refine install" when you run the initial installation.
* A `web.config` file is now included to support rewriting for those using PHP/IIS on Windows.
* Twitter Bootstrap has been upgraded to 3.0. Check for dependencies with your current code if you upgrade, oil will now generates 3.0 compliant view files.
* FuelPHP\Upload has been switched to version 2.0.1. If you upgrade, don't forget to change your composer.json and run `php composer.phar update`.
* Exception handling in the index.php now resets the main Request, to avoid subsequent Requests to be seen as HMVC calls.
* index.php now only updates the output with profiling data if the placeholders are present in the output. 
* All code using preg_replace() with the \e modifier has been rewritten to be compliant with PHP 5.5+.
* Reverse routing now works with regex routes.

### Specific classes

* New __Arr::merge_assoc()__ method as alternative to array_merge_recursive(), which does not alter numeric keys, and does not merge mixed values (see docs).
* New __Arr::reindex()__ method to recursively reindex an indexed array, or the numeric keys in an assoc array.
* New __Arr::subset()__ method returns a subset of an array based on a list of (dot-notated) keys.
* __Asset__ methods `css()` and `js()` now allow inline code to be passed as a string.
* __Cache_Storage_Memcached__ now creates and reuses a single connection to the Memcached server.
* __Cache_Storage_Memcached__ now supports a relative expiration time, like the native PHP functions.
* __Cache_Storage_Redis__ now creates and reuses a single connection to the Redis server.
* __Config__ now has a driver to store config data in a database table.
* __Date__ has a new emulation function for strptime(), for better Windows support.
* __DB__ now supports master/slave configurations, completely transparent for the application.
* __DB__ where() method now has support for DB::expr().
* __DB__ configuration now has support for speciying the collating sequence.
* New `has_connection()` method for __DB::instance()__ to check if a valid DB connection is present.
* You can now create nameless __Fieldset__ objects.
* __File_Handler_File__ now has a new method `get_path()` to retrieve the file's path.
* New __Form::csrf()__ method to add a hidden field to your form with the CSRF token.
* __Form::select()__ now allows you to pass a default value via the attributes array.
* __Form::to_xml()__ now has a configuration option to escape data using CDATA instead of converting to HTML entities.
* __Fuel__ has improved base_url detection, to work better with installations inside the document root.
* New __Image__ `extension()` method to retrieve the extension of the loaded image.
* __Input__ now supports the HTTP method PATCH.
* __Lang__ now has a driver to store language data in a database table.
* __Log__ now tells you why it couldn't open or write to the logfile.
* __Migrate__ now prints a warning if one or more migration steps were skipped.
* __Mongo_Db__ now uses the MongoClient class, instead of the deprecated Mongo class.
* __Pagination__ now casts all numeric values to int after calculation.
* __Pagination__ now has a `__toString()` method to render when the object is cast to string.
* __Pagination__ render methods now correctly use the configured default values when called without arguments.
* __Pagination::render()__ now has the option to return the raw pagination data array instead of the rendered HTML.
* New config section for __Pagination__ to support Bootstrap v3.
* New __Redis_Db__ method `psubscribe()` allows you to listen and define a callback for every response.
* __Request__ now writes the request type to the log, together with the requested URI.
* __Request__ now checks if all required action arguments are present, and throws HttpNotFoundException if not.
* __Response__ now has an updated HTTP status code list (including the famous 418!).
* The use of wildcards in a URI for __Response::redirect()__ is now configurable.
* __Security::xss_clean()__ is now using htmLawed v1.1.16.
* New options parameter for __Security::xss_clean()__ to pass custom configuration to htmLawed.
* __Security::check_token()__ now uses Input::param() to fetch the token. This allows HTTP methods other then POST to be secured with a token.
* New __Str::random()__ feature to generate UUID v4 strings.
* __Uri::segment_replace()__ now allows you to force the URL scheme to HTTP or HTTPS.
* New __Uri::update_query_string()__ method allows you to add query string data to an existing URL.
* __Validation::valid_date()__ will now ensure the date value format is valid.
* New __Validation::get_error_message()__ to retrieve all or individual error message from validation error objects. This saves you having to loop over the objects in your code to get the messages out.
* __Validation::valid_string()__ now has support for "brackets" and "braces".
* __Viewmodel::forge()__ now allows you to pass a custom view name or View object.
* New __Theme::viewmodel()__ method allows you to create theme aware Viewmodel objects.
* __Upload__ init method has been modified to make sure uploaded files are not processed twice.
* New __Uri::build_query_string()__ method to generate a query string from a list of arrays or strings.

### Packages

* __Auth__: Opauth driver now has a config switch to allow auto registration after OAuth login.
* __Email__: Added support for images in base64 encoding.
* __Email__: Fixed additional blank line in the mail header causing some mailservers to barf...
* __Email__: New getter methods `get_from()`, `get_to()`, `get_cc()`, `get_bcc()`, `get_subject()`, `get_body()` and `get_reply_to()`.
* __Oil__: There is now support for the `_init()` static method for tasks.
* __Oil__: `test` now has (expiremental) support for __phpunit.phar__.
* __Oil__: Scaffolding menu links are now rendered as buttons.
* __Oil__: Running `create` inside a valid FuelPHP installation now displays an error message.
* __Oil__: Generating a migration without the correct arguments now fails with an error message.
* __Oil__: Scaffolding and Admin scaffolding now generate Bootstrap v3 compliant view files.
* __Oil__: New `package` command generates a skeleton for a new package.
* __Oil__: You can now use dashes and underscore in generate field options (p.e. enum values).
* __Oil__: All commands now have a help screen.
* __Orm__: __Observer_Typing__ now creates an empty array when calling unserialize() on a NULL column value.
* __Orm__: `get_one()` now uses `rows_limit(1)` when fetching an object with related objects.
* __Orm__: New `Model_Soft::purge()` method allows bypassing the soft-delete functionality and delete an object permanently.
* __Orm__: `Model::to_object()` can now be called with the same arguments as `to_array()`.
* __Orm__: `group_by()` Query method now supports relation name prefixes for column names.
* __Orm__: You can now pass additional conditions to a lazy get() of a related object.
* __Orm__: __Model_Soft__ now has support for `count()`, `min()` and `max()`.
* __Orm__: `min()` and `max()` results are __no longer__ cast to int, so they can be used on date columns.
* __Orm__: `Model::to_array()` has better object tracking to prevent recursion.
* __Orm__: `from_array()` now has support for the EAV extension. Importing non-model properties will now create EAV records instead of custom data if the model has an EAV container configured.
* __Orm__: There is now support for EAV containers in `Model_Nestedset`.

## v1.6

[Full List of core changes since 1.5](https://github.com/fuel/core/compare/1.5/master...1.6/master)

### Important fixes, changes, notes. Read them carefully.

* This release officially introduces Composer to FuelPHP. You will __have__ to install it, and run a 'php composer.phar update' to pull in any required packages. Without this step, __1.6 WILL NOT WORK!!!__
* Class names in the __Auth__ package have been modified to match FuelPHP coding standards. Check your configuration ('SimpleAuth' is now 'Simpleauth'!) and any class extensions you have made.
* The __Log__ functionality has been moved back in the core. If you are upgrading from 1.5, please remove the old 'Log' package from the ``always_load`` section in your ``config.php``, and remove the package from the packages folder.
* The environment 'stage' has been renamed to 'staging', the corresponding constant to Fuel::STAGING.
* You now get a proper error message if your PHP timezone settings are not correctly configured.
* You now get a proper error message if there is an issue with rights to the log file.
* All code that creates files or directories has been rewritten to properly set the configured permission mask without using `umask()`, which is not thread-safe.

### Backward compability notes

* The names of the __Auth__ classes have been changed to comply with FuelPHP coding standards (`Auth_Login_Simpleauth` instead of `Auth_Login_SimpleAuth`), this can cause a class-not-found error if you have extended an Auth class in your application.
* The ORM `validation_observer` now has multiple events. Do not define it without specifying which events to call, as it would cause validation to be called twice!
* The __Orm__ behaviour with regards to relation assignments has been changed. Now, when you do an unset(), a set to NULL or array(), or you assign a new value, the previous relation will be unset. Regardless of whether you had fetched that relation or not. This might impact your application if you have used this 'bug' as a shortcut to adding additional objects to an existing relation!

### Removed code (because it was deprecated in v1.5 or earlier)

* __Orm__: `find()` and `find(null)` functionality is now removed. Use `query()` instead.

### Code deprecated in v1.6 (to be removed in the next release)

* __ViewModel__: when determining the name of the ViewModel class to load, it will search for classes with and without the 'View_' prefix. This behaviour is deprecated, as of the next release ViewModel classes MUST be in classes/view, and MUST be prefixed with 'View_'.

### Security related

* The default security filters have been removed from the core configuration, to allow you to define your own security filters. **Note:** if you're migration from previous versions and relied on this default config, make sure your app config file has the default security filters defined!

### System changes

* The Markdown library has been upgraded to 1.2.6.
* The cache option in the global configuration now correctly caches finder paths to speedup file lookups.
* Controller methods can now return 'false' or 'array()' as valid values, for use in HMVC requests.
* Exceptions in shutdown event are now properly caught and handled.

### Specific classes

* __Agent__: will now re-use an expired download if a new browscap file could not be downloaded.
* __Arr__: New `search` method allows you to search for values in array structures, and get the (dot-notated) key returned.
* __Arr__: New `unique` method allows you to de-dup an array. Like array_unique(), but this one supports objects and closures, and doesn't sort the source array first.
* __Arr__: New `sum` method allows you to sum up specific values in a multi-dimensional array structure.
* __Asset__: now generates the correct Asset URL when using a CDN.
* __Controller_Rest__: now allows auth checks using a controller method (avoids `before()` or `router()` hacks).
* __DB__: new `identifier` method allows you to properly quote an identifier for use in custom queries.
* __DB__: the `Database_Transaction` class that was already deprecated in v1.2 has been removed. All drivers support transactions natively.
* __DButil__: now supports the keyword "PRIMARY KEY" on field updates.
* __Cache__: index mechanism has been refactored. Dependency checking now works properly when using APC, Memcached or Redis backends.
* __Error__: a new configuration option allows you to render already generated output to be shown in error messages via the 'prior output' link, instead of the HTML.
* __File__: `create_dir` method now works properly on Windows.
* __Form__: `select` now doesn't use inline css to generate optgroups unless needed.
* __Fieldset__: fixed generation of invalid labels.
* __Html__: `anchor` method now generates URL's without a trailing slash.
* __Input__: `uri` method now works properly on Windows.
* __Lang__: `delete` method now works properly when passing a $group value.
* __Pagination__: now generates the last link correctly.
* __Profiler__: DB query profiling now includes a stack trace for every query to make it easier to find it in your code.
* __Router__: now supports protocol specific routes (http/https) in verb based route notation.
* __Upload__: has been rewritten to use the FuelPHP v2 composer library.
* __Viewmodel__: now calls `before` before rendering the view, instead of when constructing the object.
* __Viewmodel__: ViewModel class name is now correctly determined from the passed view name.
* __Viewmodel__: Will now look in the global namespace for the ViewModel class if called from a module and not found in the module namespace.

### Packages

* __Auth__: Class names have been modified to match FuelPHP coding standards.
* __Auth__: `update_user` now verifies if the new email address is unique before updating it.
* __Auth__: Number of PBKDF2 iterations can now be configured in the auth config file.
* __Auth__: Multiple concurrent user logins can now be configured through the driver configuration file.
* __Auth__: Auth login drivers now set 'updated_at' correctly.
* __Auth__: new `get` method allows unified access to all user properties.
* __Auth__: new `groups` method which returns the list of all defined groups.
* __Auth__: new `roles` method which returns the list of all defined roles.
* __Auth__: new 'Ormauth' driver set that uses the database through ORM as datastore.
* __Auth__: Package now contains migrations for both Simpleauth and Ormauth.
* __Auth__: New 'Simple2Orm' task can migrate your existing Simpleauth config to Ormauth.
* __Email__: Attachments can now be named.
* __Log__: The Log package, introduced in 1.5 as a temporary solution, has been removed again.
* __Oil__: Fixed redirect loop in the generated admin backend code.
* __Oil__: Improved exception handling and reporting.
* __Oil__: Added support for ORM soft-delete models.
* __Oil__: Modified the scaffolding templates to work better with bootstrap.
* __Oil__: New commandline options for PHPunit allow for more granular testing and logging.
* __Orm__: Validation observer now supports 'before_insert' and 'before_update'.
* __Orm__: Now correctly resets foreign keys if cascade_delete is false.
* __Orm__: Added view support to count(), min() and max() queries.
* __Orm__: min() and max() now return integers instead of strings.
* __Orm__: Added temporal support (data versioning).
* __Orm__: You can now test for existence of EAV attributes using isset().
* __Orm__: Validation observer can now validate on insert and update too.
* __Orm__: It is now allowed for models to have a FK as part of the PK.
* __Orm__: You can now order a many_many result on an attribute in the through table.
* __Orm__: You can now pass custom (non-column) data when forging a new model object.
* __Orm__: Current relations are now properly unset when using unset() or a new assignment.
* __Orm__: `from_array` now returns $this so you can chain on it.
* __Orm__: `from_array` now allows you to load custom data.
* __Orm__: `from_array` now allows you to load related objects from a multidimensional array.
* __Orm__: Several speed improvements in Observer_Typing.
* __Orm__: Observer_Typing float conversions are now locale aware.
* __Orm__: Observer_Typing now uses property defaults on null values if defined.
* __Orm__: Observer_Typing can now handle MySQL '0000-00-00 00:00:00' datetime values.
* __Orm__: new `from_cache` method allows you to enable/disable object caching on a query.
* __Orm__: 'join on' now works correctly as documented.
* __Orm__: 'order_by' now works correctly when a subquery is generated.
* __Orm__: `is_changed` now does loose-typing, so 1 => '1' doesn't trigger an update query anymore.
* __Parser__: Added support for mthaml (HamlTwig)
* __Parser__: Switched to using Composer for smarty, mustache, mthaml and twig template engines.
* __Parser__: Markdown has been upgraded to 1.2.6.

## v1.5

[Full List of core changes since 1.4](https://github.com/fuel/core/compare/1.4/master...1.5/master)

### Important fixes, changes, notes. Read them carefully.

* The "Undefined constant MYSQL_ATTR_COMPRESS" issue that pops up under certain conditions has been fixed.
* It has been reported that under certain circumstances there might be issues with serialized data stored in the Auth user table, field "profile_fields", and the "payload" field in the sessions table. It is strongly advised to define those columns as "blob" to avoid these issues.
* A new `Log` package has been introduced in preparation for the transition to 2.0, which replaces the `Log` class.

### Backward compability notes

* __Uri::to_assoc()__ no longer throws an exception with uneven segments, but returns ``null`` as value of the last segment
* ORM __Model::find()__ no longer accepts ``null`` as only parameter. If you want to use that, you are now REQUIRED to also pass the options array (or an empty array).
* __Sessions__ have been refactored, all validation and validation data has been moved server side. Because of this, pre-1.5 sessions are not longer compatible.
* The __Log__ class has been removed and replaced by the __log package__. If you have extended the `Log` class in your application, you will have to extend `\Log\Log` instead, and check the compatibility of your changes. If they are about logging to other locations, you might want to look into the Monolog stream handlers instead.

### Removed code (because it was deprecated in v1.4 or earlier)

* ORM __Model::find()__ can no longer be used to construct queries using method chaining. Use  __Model::query()__ instead.

### System changes

* __Controller_Hybrid__: Now sets the correct content-type header on empty responses.
* __Controller_Rest__: Now sets the correct content-type header on empty responses.

### Specific classes

* __Agent__: Will now honour 301/302 redirects when trying to fetch the browscap file.
* __Arr__: New ``filter_recursive`` method, a recursive version of PHP's ``array_filter()`` function.
* __Debug:__ ``dump()`` method now html encodes string variables.
* __Debug:__ ``dump()`` and ``inspect()`` can now be styled using CSS (a classname has been added to the div).
* __Fieldset__: New ``set_tabular_form()`` method allows creation of one-to-many forms.
* __Fieldset__: New ``get_tabular_form()`` method to check if a fieldset defines a tabular form.
* __Image__: New ``flip()`` method for vertical/horizontal image flipping.
* __Inflector__: ``friendly_title()`` now has an option to deal with non-ascii characters.
* __Inflector__: ``pluralize()`` now has an count parameter to return a singular value if the count is 1.
* __Migrate__: Now allows you to define the DB connection to be used for migrations in the global migrations config file.
* __Model_Crud__: Now has a `$_write_connection` property to support master/slave database setups.
* __Mongo_Db__: Will now log it's queries to the profiler if enabled.
* __Mongo_Db__: Now has a method ``get_cursor()`` to directly get a mongodb cursor.
* __Pagination__: Now support pagination using a Query String variable.
* __Pagination__: Now has support for first/last page links.
* __Response__: Will now add a "Content-Length" header when generating the output.
* __Session__: Now correctly erases the session cookie on a ``destroy``.
* __Session__: Now silently (re)creates the session if data is present by no session is created.
* __Session__: Cookie encryption can now be disabled using a session configuration key.
* __Session__: Session cookie now only contains the session id. Validation now happens with server-side data.
* __Session__: New configuration key `expire_flash_after_get` controls `get_flash()` expiration.
* __Session__: ``get_flash()`` now has to override the configured flash variable expiration rules.
* __Session__: ``set_flash()`` now has to partial array dot-notation support.
* __Uri__: ``to_assoc()`` now accepts a start parameter allowing you to skip leading segments.
* __Validation__: Now has a new built-in rule 'numeric_between' allowing you to specify a range.
* __Database_Query_Builder_Join__: Now supports both AND and ON chaining of join condition.

### Packages

* __Orm__: Supports the new tabular form fieldset in it's models.
* __Orm__: ``find()`` options array now has support for 'group_by'.
* __Orm__: New ``Model_Soft`` implements soft-delete functionality (thanks to Steve West).
* __Orm__: ``from_array()`` can now also populate related objects.
* __Orm__: `Model` now has a `$_write_connection` property to support master/slave database setups.
* __Oil__: ``oil install`` now installs packages without 'fuel_' prefix too.
* __Oil__: scaffolding now supports subdirectories.
* __Oil__: Now has a config file that allows you to configure the location of phpunit.
* __Oil__: Now has a task `fromdb` that can generate models, migrations, scaffolding or admin from an existing database.
* __Parser__: Twig driver has been updated to work with Twig v1.12.0.

## v1.4

[Full List of core changes since 1.3](https://github.com/fuel/core/compare/1.3/master...1.4/master)

### Important fixes or changes

* fixed DB class error about missing __PDO::MYSQL_ATTR_COMPRESS__ constant
* you are now __REQUIRED__ to set a correct php timezone. The FuelPHP default value of 'UTC' has been removed, as it would cause date conversion errors that are difficult to find. Most notable, you will have issues with session and cookie expiration.
* __ALL__ default configuration has been moved to core/config. Only use the app/config folder for application specific overrides of default values, or for custom configuration.

### Backward compability notes

This release features a new Pagination class that isn't completely backward compatible with the API from previous versions. We have put a lot of effort in emulating the old behaviour of the class, but as PHP doesn't support magic getters/setters for static properties, you'll have to replace those in your code manually when you upgrade to v1.4. The required changes can be found in the [documentation](http://docs.fuelphp.com/classes/pagination.html).

### Removed code (because it was deprecated in v1.3)

* Removed "auto_encode_view_data" config key, deprecated in v1.2
* __Fuel__: Removed ``Fuel::add_module()``, deprecated in v1.2. Use ``Module::load()`` instead.
* __Fuel__: Removed ``Fuel::module_exists()``, deprecated in v1.2. Use ``Module::exists()`` instead.
* __Theme__: Removed ``$theme->asset()``, deprecated in v1.2. Use ``$theme->asset_path()`` instead.
* __Theme__: Removed ``$theme->info()``, deprecated in v1.2. Use ``$theme->get_info()`` instead.
* __Theme__: Removed ``$theme->all_info()``, deprecated in v1.2. Use ``$theme->load_info()`` instead.
* __Orm\Model__ : Removed ``$model->values()``, deprecated in v1.3. Use ``$model->set()`` instead.

### Code deprecated in v1.4 (to be removed in the next release)

* __Redis__: ``Redis::instance()`` will no longer create new objects. Use ``Redis::forge()`` for that.
* __Orm\Model__: Using the ``find()`` method without parameters is deprecated. Use ``query()`` instead.

<<<<<<< HEAD
### Security related

* tbd

=======
>>>>>>> fuel/1.5/master
### System changes

* __Config__ and __Lang__ loading with forced reload now bypasses the file cache and always reload.
* __Controller_Hybrid__: Is now fully hybrid, with support for get/post methods, and no longer restricted to ajax calls when returning json.
* __Fieldset__, __Form__ and __Validation__ now have full support for input tags using array notation.
* __Input__ and __Route__ now support a new configuration key ``routing.strip_extension`` to control wether or not the extension must be stripped from the URI.
* __Lang__: fixed double loading of language files when the active and fallback language are the same.
* __Pagination__: Class completely rewritten, now with instance and template support.
* __Uri__: Has improved extension processing, and now handles dots in URI parameters correctly.
* The active language is now a per-request setting instead of a global setting. Changing it in an HMVC request will no longer affect the language setting of the parent request.

### Specific classes

* __Arr__: New ``filter_suffixed()`` method to filter an array on key suffix.
* __Arr__: New ``remove_suffixed()`` method to remove keys from an array based on key suffix.
* __Asset__: DOCROOT can now be specified as the asset root path (by using "").
* __Controller_Rest__: Now allows you to specify a basenode when returning XML.
* __DB__: ``select()`` now has an option to reset previous selects.
* __DB__: Added ``error_info()`` to return information about the last error that occurred.
* __DB__: ``join()`` can now be used without conditions for a full join.
* __DB__: ``group_by()`` now supports passing an array of columns.
* __Fieldset__: New ``enable()``/``disable()`` methods to control which fields will be build.
* __Fieldset__: New ``get_name()`` method allows retrieval of the fieldset object name.
* __Fieldset__: ``set_config()`` and ``get_config()`` now support dot-notation for accessing config values.
* __Finder__: Fixed PHP notices after removing a finder search path.
* __Format__: Added JSONP support.
* __FTP__: Now supports a timeout on the connect.
* __Image__: Fixed forcing an image extension when using ImageMagick.
* __Inflector__: ``friendly_title()`` now has the option not to filter non-latin characters.
* __Input__: Fixed skipping IP validation when reserved_IP ranges were excluded.
* __Lang__: Now supports multiple languages concurrently. Loaded files for a given language code will no longer be overwritten when you switch the active language.
* __Lang__: ``load()`` method now also returns the loaded group on subsequent calls.
* __Markdown__: Has been upgraded to v1.2.5.
* __Migrate__: Fixed PHP notice when a non-existent package was specified.
* __Migrate__: An up or down migration can now be rejected by returning ``false``.
* __Migrate__: Added support for processing out-of-sequence migrations.
* __Redis__: Now has a ``forge()`` method to create multiple instances.
* __Redis__: Added support for Redis authentication.
* __Response__: If the body contains an array it will be converted to a string representation before outputting it.
* __Response__: ``redirect()`` now supports wildcards in the URL.
* __Router__: Re-introduced support for routing using URI extensions.
* __Session__: Fixed passing a session cookie via POST to allow access to the session by flash objects.
* __Session__: Added support for dot_notation to ``get_flash()``.
* __Session__: Fixed flash variables not being stored when retrieved in the same request.
* __Session__: Fixed session key data not available for new sessions until after a page reload.
* __Str__: Now has an ``is_xml()`` method.
* __Theme__: Is now module aware, and can prefix view paths with the current module name.
* __Upload__: ``process()`` now throws an exception if ``$_FILES`` does not exist (due to missing form enctype)
* __Uri__: New ``segment_replace()`` method allows for replacement of wildcards by current segments.
* __View__: ``get()`` now returns all variables set when no variable name is given.
* __Viewmodel__: ``get()`` now returns all variables set when no variable name is given.

### Packages

* __Auth__: No changes.
* __Email__: Added a Noop dummy driver, which can be used to prevent test emails going out.
* __Oil__: Added "generate TASK" option to generate task classes.
* __Oil__: Added support for Viewmodels to scaffolding.
* __Oil__: Fixed errors on ``false`` results in the console.
* __Oil__: Added support for "drop_{field}_from_{table}" to migrations.
* __Oil__: oil -v now also displays the current environment setting.
* __Oil__: New --singular option to force the use of singular names in scaffolding.
* __Orm__: Fixed PK overwrite issue when PK is not auto_increment.
* __Orm__: Observer_Slug now supports the ``before_update`` trigger.
* __Orm__: Added support for filter conditions to the model through the ``$_conditions`` property.
* __Orm__: Fixed incorrect sequence of multiple ``order_by()`` clauses.
* __Orm__: Implemented full support for partial selects.
* __Orm__: Fixed circular reference problem when using ``to_array()`` with included relations that self reference.
* __Orm__: ``get_one`` now uses ``rows_limit()`` instead of ``limit()`` when set.
* __Orm__: Model objects now support custom properties
* __Orm__: Added support for custom properties to ``to_array()``
* __Orm__: ``is_changed()`` now deals better with null values.
* __Orm__: Introduced support for EAV containers (emulation of EAV via one or more related tables)
* __Orm__: ``get_diff()`` now deals better with unset relations.
* __Orm__: Relations of new objects can now be fetched if the FK is known.
* __Orm__: Added support for ``group_by()``.
* __Parser__: ``forge()`` functionality now equals that of ``View::forge()``.
* __Parser__: Markdown has been upgraded to v1.2.5.

## v1.3

[Full List of core changes since 1.2](https://github.com/fuel/core/compare/1.2/master...1.3/master)

### Removed code (because it was deprecated in v1.2)

* __Controller__: Deprecated `$response` property has been removed from all base controller classes. All controller actions now HAVE TO return their results, either a `Response` object, or something that can be cast to string. If you are still on pre v1.2 controller code, your application will **NO LONGER** work after the upgrade to v1.3.

### Code deprecated in v1.3 (to be removed in v1.4)

* __Orm__: Model method `values()` has been deprecated. Use `set()` instead.

### Security related

* __PHPSecLib__: Has been updated to v0.2.2.
* __HTMLawed__: Has been updated to v1.1.12.

### System changes

* __Debug___: You can now modify the default display behaviour of `dump()` through `Debug::$js_toggle_open`.
* __Upload__: Now allows you to set custom messages in validation callbacks.
* __Config__: `Config::load` now always returns the loaded configuration.
* __Pagination__: Now uses anchors for all pagination enties, which allows for better styling.

### Specific classes

* __Arr__: `Arr::pluck` has been added.
* __Arr__: `Arr::remove_prefixed` has been added.
* __Arr__: `Arr::insert_assoc` has been added.
* __Asset__: Has been updated to work better on Windows.
* __Asset__: `Asset::find_file` has been added.
* __Asset__: `Asset::add_type` has been added.
* __DB__: `DB::in_transaction` has been added.
* __DB__: Added support for compressed MySQL connections through the new `compress` config key.
* __Error__: PHP notices/warnings/errors are now caught and thrown as an Exception.
* __Event__: The Event class has been converted to be instance based.
* __Fieldset__: You can now choose to overwrite existing options when using `set_options`.
* __File__: download() has been made to work when shutdown events are defined that set headers.
* __Image__: New option on load() to force a file extension.
* __Format__: CSV file handling has been improved.
* __Log__: Now supports custom log levels.
* __Log__: Now allows you to configure an array of specific log levels to log.
* __Migrate__: Now supports multiple package paths.
* __Mongo_Db__: `Mongo_Db::get_collection` has been added.
* __Pagination__: Added `attrs` keys to the configuration to define custom anchor attributes.
* __Redis__: Added support for connection timeouts through the new `timeout` config key.
* __Str__: `Str::starts_with` has been added.
* __Str__: `Str::ends_with` has been added.
* __Str__: `Str::is_json` has been added.
* __Str__: `Str::is_html` has been added.
* __Str__: `Str::is_serialized` has been added.

### Packages

* __Auth__: `get_profile_fields()` now allows you to fetch a single profile field.
* __Email__: New `NoOp` email driver allows testing without sending emails out.
* __Oil__: Now returns a non-zero exit code on failures.
* __Oil__: Added support for PHPunit clover, text and phpformat Code Coverage methods.
* __Orm__: New model method `register_observer()` and `unregister_observer()` to define new observers at runtime.
* __Orm__: Added support for `where` and `order_by` clauses to relation conditions.
* __Orm__: `set()` method has been updated to provide the same API as **Model_Crud**.
* __Orm__: PK's are now typecast on retrieval if a type has been defined in the properties.
* __Orm__: Update query code has been improved for better support of PostgreSQL.
* __Parse__: Smarty driver now supports the `plugin_dir` path.

## v1.2

[Full List of core changes since 1.1](https://github.com/fuel/core/compare/1.1/master...1.2/master)

### Removed code (because it was deprecated in v1.1)

* All `factory()` methods. The have been replaced by `forge()`.
* __Agent__::is_mobile(). Replaced by `is_mobiledevice()`.
* __Arr__::element(). Replaced by `get()`.
* __Arr__::elements(). Replaced by `get()`.
* __Arr__::replace_keys(). Replaced by `replace_key()`.
* __Controller__::render(). Is no longer used as actions need to return a Response object now.
* __Database_Connection__::transactional(). Was already a NOOP.
* __DB__::transactional(). Called Database_Connection::transactional().
* __Fieldset__::errors(). Replaced by `error()`.
* __Fieldset__::repopulate(). Undocumented parameter was removed, functionality is offered by `populate()`.
* __Fuel__::find_file(). Replaced by `Finder::search()`.
* __Fuel__::list_files(). Replaced by `Finder::instance()->list_files()`.
* __Fuel__::add_path(). Was used by `find_file()`, no longer needed.
* __Fuel__::get_paths(). Was used by `find_file()`, no longer needed.
* __Fuel__::add_package(). Replaced by `Package::load()`.
* __Fuel__::remove_package(). Replaced by `Package::unload()`.
* __Fuel_Exception__ class. Replaced by `FuelException`.
* __Input__::get_post(). Replaced by `param()`.
* __Lang__::line(). Replaced by `get()`.
* __Request404Exception__ class. Is replaced by `HttpNotFoundException`.
* __Uri__ properties $uri and $segments are now protected. Use Uri::get() and Uri::get_segment() or Uri::get_segments().
* __Validation__::errors(). Replaced by `error()`.
* __Viewmodel__ property $_template. Is replaced by `$_view`.
* __Viewmodel__::set_template(). Replaced by `set_view()`.

### Code deprecated in v1.2 (to be removed in v1.3)

* __Pagination__: Class will be removed and replaced by a new `Paginate` class.
* __Fuel__::add_module(). Is replaced by `Module::load()`.
* __Fuel__::module_exists(). Is replaced by `Module::exists()`.
* __Theme__::asset(). Replaced by `asset_path()`.
* __Theme__::info(). Replaced by `get_info()`.
* __Theme__::all_info(). Replaced by `load_info()`.

### Security related

* Security class now __requires__ you to define the `security.output_filter` application config setting. An exception is thrown if it isn't present.
* Security::htmlentities() now defaults to use ENT_QUOTES instead of ENT_COMPAT as flag. This is configurable in the second argument for the method and the default can be overwritten in config as `security.htmlentities_flags`.

### System changes

* __Controller__: action methods, or the controllers `after()` method if present, now must return a `Response` object.
* __Controller__: `before()` and `after()` methods are now optional, as documented.
* __Controller_Hybrid__: combines `Controller_Template` and `Controller_Rest` in a single base controller for mixed HTTP and REST responses.
* __Controller_Rest__: added a fallback to `"action_"` when no HTTP method action is found.
* __Controller_Rest__: you can now define custom HTTP status codes.
* __Controller_Template__: the `$auto_render` setting has been removed, to prevent rendering return whatever you want to use instead.
* __Database__: The PDO driver now supports `list_columns()`.
* __Module__: new `Module` class to load or unload modules.
* __Uri__: the URL extension is no longer part of the URI. A new `extension()` method allows you to fetch it.
* __Request__: `Request_Curl` now properly deals with succesful requests that return a 4xx or 5xx HTTP status.
* __Request__: `Request_Curl` and `Request_Soap` now supports returning header information. A `get_headers()` has been added to fetch them manually.
* __Router__: can now be configured to treat URI's without regards to case.

### Specific classes

* __Arr__: `Arr::to_assoc()` now throws a BadMethodCallException on bad input.
* __Arr__: `Arr::assoc_to_keyval()` now requires all parameters and first parameter must be an array or implement `Iterator`.
* __Arr__: Added `reverse_flatten()`, `is_assoc()` and `insert_before_key()` methods.
* __Arr__: Added `in_array_recursive()` to do a recursive `in_array()` lookup.
* __Asset__: Separated into the static front (`Asset`) and dynamic instance (`Asset_Instance`).
* __Asset__: Separated into the static front (`Asset`) and dynamic instance (`Asset_Instance`).
* __Asset__: `css()`, `js()` and `img()` methods are now chainable.
* __Asset__: you can now specify a URL as location, for CDN support.
* __Asset__: new `fail_silently` config value allows you to skip missing assets.
* __Cli__: now supports ANSICON on Windows for colored commandline output.
* __Config__: is now driver based to support `php`, `ini`, `yaml` and `json` type configs.
* __Config__: now allow you to load a file by FQFN.
* __Cookie__: all cookie data can now be fetched like Input class does.
* __Date__: All fuel notices have been replaced by `UnexpectedValueException`s.
* __Date__: On windows an extra fallback has been added for the `create_from_string()` method.
* __Date__: new `display_timezone()' and `get_timezone_abbr()`, and changes to support working with multiple timezones.
* __DB__: `cache()` now has the option not to cache empty resultsets.
* __DB__: `where()` do now support closures to specify the where clause.
* __DB__: Update now supports `limit()` and `order_by()`.
* __DB__: now tries to reconnect when a disconnected DB connection is detected.
* __DButil__: `create_database()` now supports 'IF NOT EXIST'.
* __DButil__: Better support for the CONSTRAINT keyword.
* __DButil__: new `add_foreign_key()` and `drop_foreign_key()` methods.
* __Event__: shutdown events are now also executed after `exit` and `die` statements.
* __Fieldset__: added `set_fieldset_tag()` to define the fieldset tag.
__Fieldset__: added `add_before()` and `add_after()` methods to insert a new field before/after a specific field.
* __Fieldset_Field__: added `add_description()` method and `{description}` tag to templates.
* __Fieldset_Field__: added `add_error_message()` method to create error message overwrites per field.
* __File__: `download()` now allows you to continue processing after calling it.
* __Form__: Separated into the static front (`Form`) and dynamic instance (`Form_Instance`).
* __Inflector__: now supports Hungarian accepted characters when converting to ascii.
* __Input__: `method()` now supports the `X-HTTP-Method-Override` header.
* __Input__: new `json()` and `xml()` methods to fetch json or xml from the HTTP request body.
* __Lang__: `load()` method now supports overwriting when merging language files.
* __Lang__: now allow you to load a file by FQFN.
* __Lang__: is now driver based to support `php`, `ini`, `yaml` and `json` type language files.
* __Lang__: language files can now be saved (as `php`, `ini`, `yaml` or `json`) using `save()`.
* __Migrate__: now tracks individual migrations, so they don't have to have a sequence number anymore.
* __Model_Crud__: now supports `created_at` and `updated_at` fields, like `ORM\Model` does.
* __Model_Crud__: now has full callback support.
* __Model_Crud__: you can now run validation separately (`::validates`) and skip validation when saving a model.
* __Profiler__: profiler logging methods are now NO-OP's when the profiler is not loaded.
* __Profiler__: now writes it's output under the page content, instead of using an overlay.
* __Session__: Added session task to create and remove sessions table.
* __Session__: New sessions are not saved until there is data present in the session.
* __Theme__: Separated into the static front (`Theme`) and dynamic instance (`Theme_Instance`).
* __Theme__: now supports installation outside the docroot (for views).
* __Theme__: now uses the `Asset` class to load theme assets.
* __Theme__: instances now support templates, template partials and partial chrome templates.
* __Validation__: You can now disable fallback to global input using the 'validation.global_input_fallback' config setting.

### Packages

* __Auth__: Auth login drivers no have a `validate_user` method to validate a user/password without setting up a logged-in session.
* __Auth__: SimpleAuth `SimpleUserUpdateException`s are now numbered to be able to identify the exact error after catching the exception.
* __Email__: Now handles SMTP timeouts properly.
* __Email__: You can now specify the return address.
* __Email__: Now handles BCC lists correctly when using SMTP.
* __Email__: Respects new lines in alt body better.
* __Email__: You can now specify the return address.
* __Oil__: Use `phpunit.xml` from `APPPATH` if present when running unit tests.
* __Oil__: Reinstated `oil package` command to install packages from git repositories.
* __Oil__: You can define the environment the command has to run in using the `-env` commandline switch.
* __Oil__: Scaffolding now supports both `Model_Crud` and `Orm\Model`.
* __Oil__: Scaffolding now supports adding created-at and updated-at.
* __Oil__: Scaffolding now supports skipping the creation of a migration file using `-no-migration`.
* __Oil__: There is now a core task to generate the table for the database session store.
* __Orm__: New model method `is_fetched()` checks if relation data is fetched without triggering a new query.
* __Orm__: Validation section of the properties has a new key `skip` to indicate the field should not be validated.

## v1.1

[Full List of core changes since 1.0.1](https://github.com/fuel/core/compare/1.0/master...1.1/master)

### System changes

* Deprication of `Request::show_404()`, replaced with `throw new HttpNotFoundException` that has a handle method to show the 404
* Support for `handle()` method that is run when an exception isn't caught before `Error::exception_handler()` catches it.
* Support for special `_404_` route now in `public/index.php` thus no longer part of the core but still supported as a 'official default'
* Closures are now also supported in routes, thus routing to a Closure instead of a controler/method uri. Also added support for any type of callable in Route extensions you write yourself.
* Closure support in all getters & setters: if you get a value and also input a default the default can also be a Closure and you'll get the result of that. For setters the input can also be a closure and the result of the Closure will be set. (except for `View::set()` as one might want to pass a closure to the View)
* Moved the Environment setting from the `app/config/config.php` file to the `app/bootstrap.php` file.
* All `factory()` methods have been renamed to `forge()`.  This name better states the method's function.  The `factory()` methods are still there for backwards compatibility, but are deprecated and will log warning messages when used.
* The `$this->response` Response object is now deprecated.  Your action methods should return either a string, View object, ViewModel object or a Response object.
* Added the `fuel/app/vendor` directory to the default install.
* You can now have an unlimited number of sub-directories for your controllers. (e.g. `classes/controller/admin/users/groups.php` with a class name of `Controller_Admin_Users_Groups` would be at `site.com/admin/users/groups`)
* There is no longer a default controller for directories.  It used to be that going to something like `site.com/admin` would bring up `Controller_Admin_Admin` in `classes/controller/admin/admin.php`.  Now you must place that controller at it's expected location `classes/controller/admin.php` with a name of `Controller_Admin`.
* A `Controller::after()` method now gets passed the response of the controller, it must return that response (or modified) as well.
* Added new *function* `get_real_class()` to which you can pass a classname and it will return the actual class, to be used on classes of which you're not sure whether it is an alias or not.
* Module routes are prepended to the routes array when Fuel detects the fist URI segment as a module, therefor parsing them before an `(:any)` route in the app config.
* Config is now environment aware and allows partial/full overwriting of the base config from subdirectories in the config dir named after the environment.
* Added a new `Theme` class.  It allows you to easily add Theme support to your applications.
* `Fuel_Exception` has been renamed to `FuelException`
* `Fuel::find_file()` and related methods are now deprecated.  Use the `Finder` class instead (e.g. `Finder::search()`).
* Migrations are now supported in Modules and Packages
* Routing has 3 new shortcuts:
	* `:almun` matches all utf-8 alphabetical and numeric characters
	* `:num` matches all numeric characters.
	* `:alpha` matches all utf-8 alphabetical characters
* Put the `Autoloader` class into `Fuel\Core` to allow extending it, it must now be required in the app bootstrap file which is also the location where you must require your own extension.

### Security related

* Added Fuel's own response object class `Fuel\Core\Response` to default whitelist in `app/config/config.php` of objects that aren't encoded on output by the View when passed.
* The `security.auto_encode_view_data` config option in `app/config/config.php` has been renamed to `security.auto_filter_output`.
* `stdClass` was part of the default whitelisted classes from output encoding, this was a bug and it has been removed.

### Specific classes

* __Arr__: Added methods `Arr::get()`, `Arr::set()` and `Arr::prepend()`.
* __Arr__: `Arr::element()` and `Arr::elements()` have been deprecated.  Use the new `Arr::get()` instead.
* __Database__: Using transactions will no longer prevent exceptions, exceptions are thrown and should be handled by the dev. The `Database_Transaction` class has been deprecated as it has little use because of this change.
* __File__: `File::read_dir()` (and related methods on Area and Directory handler) now return dirnames with directory separator suffix
* __Fieldset_Field__: Parsing of validation rules has been moved from `Fieldset_Field::add_rule()` to `Validaton::_find_fule()`, from the outside the method still works the same but notices for inactive rules are now only shown when running the validation.
* __Form__: Added inline error reporting, which must first be switched on in config and will replace an `{error_msg}` tag
* __Form__: New default form template which puts it inside a table.
* __Fuel__: Added `Fuel::value()` which checks if the given value is a Closure, and returns the result of the Closure if it is, otherwise, simply the value.
* __Image__: No longer throws `Fuel_Exception` for any type of exception but instead `RuntimeException`, `InvalidArguementException` and `OutOfBoundsException` where appropriate.
* __Input__: `Input::post(null)` doesn't work to get full post array anymore, just `Input::post()` without params - same for all other Input methods
* __Input__: `Input::get_post()` has been deprecated and replaced by `Input::param()`.  It now also includes PUT and DELETE variables.
* __Input / Uri__: `Uri::detect()` moved to `Input::uri()` as it is part of the input and thus should be part of the input class
* __Request__: You can now also do external requests through the Request class, for now only a curl driver: `Request::forge('http//url', 'curl')` or `Request::forge('http//url', array('driver' => 'curl', 'method' => 'post', 'params' => array())`.
* __Validation__: `Validation::errors()` is depricated and replaced by singular form `Validation::error()` to be more in line with other class methods
* __Validation__: New 3rd parameter added to `Validation::run()` that allows adding callables for the duration of the run.
* __View__: The view class has been refactored and works much better now.  Output filtering is vastly improved.
* __View__: `View::capture()` has been split into two protected instance methods: `process_file()` and `get_data()`.  You will need to update your View class extensions.
* __View__: `View::$auto_encode` has been removed.  It has been replaced but auto_filter, which is per-view instance.
* __ViewModel__: Refactored the class internals to work more transparently with the `View`.
* __ViewModel__: Deprecated `$this->_template` and renamed it to `$this->_view`.
* __ViewModel__: Updated to work with the refactored `View` class.  Added `$this->bind()`.
* __ViewModel__: Deprecated `$this->set_template()` and renamed it to `$this->set_view()`.
* __Html__: Removed (not deprecated) the following methods: `Html::h()`, `Html::br()`, `Html::hr()`, `Html::nbs()`, `Html::title()`, `Html::header()`.  You should simply write the HTML yourself.
* __Config__: Added Config file drivers for PHP, INI, JSON and Yaml.  They are detected by file extension (e.g. `Config::load('foo.yml')` will load and parse the Yaml).

### Packages

* __Auth__: Renamed default table name from `simpleusers` to `users`.
* __Auth__: Added config options for DB connection and table columns used for fetching the user.
* __Auth__: Removed default config for groups & roles in `simpleauth.php` config file, only commented out examples left.
* __Orm__: Lots of tweaks to `Observer_Validation` related to changes to `Validation` & `Fieldset_Field` classes. Also changed it to only save properties that are actually changed.
* __Orm__: The `ValidationFailed` thrown when the `Observer_Validation` fails now includes a reference to the Fieldset instance that failed: `$valfailed->get_fieldset();`
* __Orm__: Added support for changing the type of join used when fetching relations, example: `Model_Example::query()->related('something', array('join_type' => 'inner'))->get();`
* __Orm__: Observers are no longer singleton but one instance per model with per model settings, check docs for more info.
* __Parser__: Added Parser package to the default install.
* __Parser__: Mustache is now part of the Parser package by default.  Version 0.7.1.
* __Email__: The Email package is added.

## v1.0

### Core

[Full Changelog](https://github.com/fuel/core/compare/v1.0-rc3...v1.0)

### Auth

[Full Changelog](https://github.com/fuel/auth/compare/v1.0-rc3...v1.0)

### Oil

[Full Changelog](https://github.com/fuel/oil/compare/v1.0-rc3...v1.0)

### Orm

[Full Changelog](https://github.com/fuel/orm/compare/v1.0-rc3...v1.0)


## v1.0-RC3

### Core

[Full Changelog](https://github.com/fuel/core/compare/v1.0-rc2.1...v1.0-rc3)

### Auth

[Full Changelog](https://github.com/fuel/auth/compare/v1.0-rc2...v1.0-rc3)

### Oil

[Full Changelog](https://github.com/fuel/oil/compare/v1.0-rc2...v1.0-rc3)

### Orm

[Full Changelog](https://github.com/fuel/orm/compare/v1.0-rc2...v1.0-rc3)


## v1.0-RC2.1

### Core

* Fixed a security issue where the URI was not being properly sanitized.

## v1.0-RC2

### Core

* oil refine install now makes the config directory writable. (Dan Horrigan)
* Added auto-id to select fields (Kelly Banman)
* Fixed typo in ::analyze\_table (Frank de Jonge)
* replaced the regex that processes :segment in the Route class. closes #33. (Harro Verton)
* Closes #31: logic error caused the Crypt class to update the config when nothing is changed. (Harro Verton)
* Fixed up XML output so that singular versions of basenode names are used when a numeric value is provided as a key.XML doesn't like numeric keys and item, item, item is boring. Also moved formatting logic out of the REST library. (Phil Sturgeon)
* Added Format::to\_php(). (Phil Sturgeon)
* Updated Form config file to work with the Form class we've had for the past 3 months (oops). Fixes #93 (Jelmer Schreuder)
* Fixes #115: Form::button() now produces a &lt;button&gt; tag instead of &lt;input&gt; (Harro Verton)
* Fixed #116: Throw an error if File::update can't open the file for write (Harro Verton)
* Added a check to File::open\_file() to make sure $resource is a valid resource before we attempt to flock() (Harro Verton)
* Fixed badly named variable in profiler. (Phil Sturgeon)
* Show full file paths in the Install task. No security concern if you're already in the terminal. (Phil Sturgeon)
* Fixed bug in \Date::create\_from\_string() where the date produced would always be exactly one month behind the actual date. (Ben Corlett)
* updated the Crypt class to make the generation of the random keys more secure (Harro Verton)
* fixed error in Fuel::find\_file(), causing a PHP notice on repeated finds (Harro Verton)
* The DBUtil class now respects the table prefix if set (Fixes #103). (Dan Horrigan)
* If an empty string is passed to Format::factory('', 'xml') it will no longer error, just return an empty array. (Phil Sturgeon)
* Added PHPSecLib to vendor to provide encryption features if no crypto is available in PHP. (Harro Verton)
* Rewritten the crypto class to use AES256 encryption, and a HMAC-SHA256 tamper validation hash. (Harro Verton)
* Added Redis to the bootstrap. (Jelmer Schreuder)
* Made Inflector::camelize() return camelcased result again but the Inflector::classify() won't use it anymore and still respect underscores. (Jelmer Schreuder)
* Allow setting labels as array including attributes instead of just tring in form->add (Jeffery Utter)
* Fix Date class. strptime returns years since 1900 not 1901. Dates were a year in the future. (Jeffery Utter)
* Options wasn't being passed when adding a radio.. thus it wasn't making all the separate fields. (Jeffery Utter)
* fixes bug #96: advanced regex must use non greedy match to properly match segments (Harro Verton)
* fixes bug #99: PHP notice due to not-initialized property (Harro Verton)
* Using memory\_get\_peak\_usage() instead of memory\_get\_usage() for more reliable memory reporting. (Jelmer Schreuder)
* Form generation: Fixed issue with "type" attribute set for textareas and selects. Also prevented empty for="" attributes by ignoring null values. (Jelmer Schreuder)
* Moved page link creation into separate method for more flexibility (Kelly Banman)
* fixed broken database profiling (Harro Verton)
* Input::real\_ip() now returns "0.0.0.0" if IP detection fails (Harro Verton)
* Bugfix: hidden inputs created with the Fieldset class caused unending loops. (Jelmer Schreuder)
* Fixed a bug that caused the image library to refuse all image types. (Alexander Hill)
* Corrected typos in the image class. (Alexander Hill)
* Fuel::find\_file() now caches files found per request URI, instead of a global cache. (Harro Verton)
* Fixed a bug in the response constructor. Response body was not setting. (Dan Horrigan)
* Bugfix: Fieldset::build() didn't match Form::build() for which it should be an alias. (Jelmer Schreuder)
* Changed Controller\_Rest formatting methods from private to protected so they can be extended (Tom Arnfeld)
* Improved the Fieldset::repopulate() method to also take a Model or array instead of using the POST values. Will accept any array, ArrayAccess instance, Orm\Model or object with public properties. (Jelmer Schreuder)

### Auth

* Fixed an issue with the casing of the Simple-driver classnames. (Jelmer Schreuder)
* Fixed small bug in Auth check method. (Jelmer Schreuder)
* Bugfix: ACL rights merging went wrong because the base was a string instead of an array. (Jelmer Schreuder)

### Oil

* Updated scaffolding to work better with the new ORM package. Fix #81.
* Suppress the error message for PHPUnit in oil, if it can't load the file from include it should just error as usual. (Phil Sturgeon)
* Fixed PHPUnit, said it wasn't installed when it was. (Phil Sturgeon)
* Fix #85: Scaffolding still referred to ActiveRecord instead of Orm. (Phil Sturgeon)

### Orm

* Added to\_array() method to export current object as an array. Improved ArrayAccess and Iterable implementation to work with relations. (Jelmer Schreuder)
* Finished the unfinished \_\_clone() method. (Jelmer Schreuder)
* Fixes #84 - now an exception is thrown when an invalid Model classname is given to a relation. (Jelmer Schreuder)
* Implemented \_\_isset() and \_\_unset() magic methods for Orm\Model (Jelmer Schreuder)
* Moved Query object creation into its own method to allow the more accurate Model\_Example::query()->where()->get(). (Jelmer Schreuder)
* order\_by() didn't return $this with array input. (Jelmer Schreuder)
* Fixed issue with constructing new models without adding properties. (Jelmer Schreuder)

<h2>The light weight Lectric Framework!</h2>
<p>Lectric is a psuedo MVC PHP framework, or tentatively defined as a CARV PHP framework(controller - action|response|view). It's basic pricipal is that most web apps are tools for&nbsp;<strong>view</strong>ing something,&nbsp;performing an<strong> action&nbsp;</strong>or generating a none-view&nbsp;<strong>response</strong>. Exactly what the framework does, generate a view, response or run an action is determined by the url - described as the URL Nodes. It follows PSR-2 coding standards and PSR-4 autloading standards.&nbsp;</p>
<h2>Installation</h2>
<p>To install:</p>
<ul>
<ul>
<li>Unpack the git archive into your projects' root directory</li>
<li>Define the following (provided you need to) constants in /engine/plugin/core_config.php (create if needed):
<ul>
<li>DB_NAME - database name</li>
<li>DB_USER - database user</li>
<li>DB_PASSWORD - databse password</li>
<li>DB_HOST - database host (probably "localhost")</li>
<li>DEBUG - true or false (true displays all errors, false displays default ini errors), default to true</li>
<li>SESSION_IGNORES - flat array of /do/ directory files that require session to be *OFF*, defaults to []</li>
<li>DEFAULT_DIRECTORY - the directory under /view/ that Lectric looks for a render.php file, defaults to "default"</li>
<li>SITE_NAME - what's the application called? defaults to "Lectric"</li>
<li>SITE_LINK - the domain name, eg. foo.bar.com</li>
<li>SITE_DESCRIPTION - default description for website (useful for uncaught webapage meta descrips... - defaults to "Lectric Default Installation"</li>
</ul>
Note: You can use logic here to define both dev and production DB settings, by testing on $_SERVER['HTTP_HOST'] and providing different definitions!</li>
<li>run install.sql to set up basic database tables</li>
<li>browse to / and read the messages displayed!</li>
</ul>
</ul>
<h2>Basics</h2>
<h4>Adding classes - PSR-4</h4>
<p>Classes should be added to the framework using the PSR-4 standard with 1 caveat - no sub namespaces. For example, to use the class \yourProjectNamespace\yourClass, you must create a file yourClass.class.php in /library/yourProjectNamespace/. Inside your class file, you must then obviously declare the namespace as&nbsp;yourProjectNamespace. Most of the time you will want to extend the \Lectric\SQLQueryPDO class. Fill your boots.</p>
<h4>Working Directory</h4>
<p>To start developing with Lectric after installation, it's as simple as cracking open the /view/default/ directory and modifying the files within!</p>
<p>It is recommended that a different default directory be used, by copying /view/default/ into another directory in /view/. Usually this would be /view/public/. This is to ensure that if you pull from this repo in the future, it doesn't overwrite any changes you've made to /view/default/. <strong>Ensure to define your default directory if not using /view/default/ in /engine/plugin/core_config.php - e.g. define('DEFAULT_DIRECTORY', 'public').</strong></p>
<p><strong>**A working directory can never be called /view/do/**</strong></p>
<h4><strong>Adding Pages</strong></h4>
<p>To add webpages for use in development, add them to &lt;working_directory&gt;_views in your database. These will be available to view on the app provided the URL matches against it using the following rules:</p>
<ul>
<li>The URL is split into nodes (see /library/Lectric/view.class.php) by the character "/"</li>
<li>The number of nodes and presence of a physical directory in /view/ determines:
<ul>
<li>The URL directory - the directory (if any) a user can see in the address bar - also the values stored in &lt;FILE Directory&gt;_directories</li>
<li>The FILE directory - the directory under /view/ to look for a render.php - also which directories and views table to look at, e.g.&nbsp;&lt;FILE Directory&gt;_directories and&nbsp;&lt;FILE Directory&gt;_views</li>
<li>The page URL - the end node of the URL - also the value in the&nbsp;&lt;FILE Directory&gt;_views table in the `url` field used to select the view</li>
</ul>
</li>
<li>If browsing to /, the URL directory = "root" (see default_directories table), the page URL = "index" and the FILE directory = defined DEFAULT_DIRECTORY const</li>
<li>If there is only 1 URL Node:
<ul>
<li>If the node is a directory under /view/, then&nbsp;the URL directory = "root" (see default_directories table), the page URL = "index" and the FILE directory =&nbsp;node value (URL_NODES[0])</li>
<li>If the node is not a diurectory under /view/, then&nbsp;the URL directory = "root" (see default_directories table), the page URL = node value (URL_NODES[0]) and the FILE directory = defined DEFAULT_DIRECTORY const</li>
</ul>
</li>
<li>If there are 2 or more URL nodes:
<ul>
<li>If the first node is a Directory under /view/:
<ul>
<li>If there are 3 or more URL nodes, then&nbsp;the URL directory = second node, the page URL = end node (end(URL_NODES))&nbsp;and the FILE directory = first node value (URL_NODES[0])</li>
<li>If there are 2 URL nodes, then&nbsp;the URL directory = "root" (see default_directories table), the page URL = end node (end(URL_NODES))&nbsp;and the FILE directory = first node value (URL_NODES[0])</li>
</ul>
</li>
<li>If the first node is&nbsp;not a Directory under /view/,&nbsp;then&nbsp;the URL directory = first node value (URL_NODES[0]), the page URL = end node (end(URL_NODES))&nbsp;and the FILE directory = defined DEFAULT_DIRECTORY const</li>
</ul>
</li>
</ul>
<p>Once a record has been made in the database, you'll need to have an appropriate file structure for the /view/DEFAULT_DIRECTORY/render.php file to call upon. As with the default repo, we recommend:</p>
<ul>
<li>/view
<ul>
<li>/template
<ul>
<li>/common
<ul>
<li>/header.php etc..</li>
</ul>
</li>
<li>/includes
<ul>
<li>view specific "chunks"</li>
</ul>
</li>
<li>/views
<ul>
<li>index.php etc...</li>
</ul>
</li>
</ul>
</li>
<li>/img</li>
<li>/css</li>
<li>/js</li>
<li>/render.php</li>
</ul>
</li>
</ul>
<p>Following the Lectric sample default project, /view/default/render.php loads the view from the database and puts it in $view-&gt;page property, to be used in the following included template common files. By being in render.php, these files will be&nbsp;<strong>common</strong> to every view in the /view/default/ directory (unless you stipulate some logic in&nbsp;<em>your</em> render file). In /view/template/common/content.php (the second included common view file) you can see we're checking if a specific /view/template/views/*.php file exists by using the `url` field value&nbsp;from the loaded view. In this case, index.php&nbsp;<em>does</em> exist, so it is included. Inside /view/template/views/index.php you can see we use the `html` field value from the loaded view, plus some extra HTML directly in the view file. By adding view fields to your view tables, you can build as many insertions from the database as needed (for example, `sidebar_content`, `testimonial_snippet`). You may also want to include files from the /view/template/includes/ directory, through this is usually reserved for class html ouput functions.</p>
<h4>Do-ing things</h4>
<p>Everything in Lectric is either a "view", a "do-response" or a "do-action".</p>
<p>Views are simple, they are discreet generated webpages defined by the URL, and should be comprised of common elements (header, footer, etc), database driven elements (meta title etc) and&nbsp;<strong>includes </strong>(small bits of html that collect to make up the bulk of a view). See above.</p>
<h5>Do Response</h5>
<ul>
<li>URL Format -&nbsp; /do/response/&lt;actionGroup&gt;/&lt;action&gt;</li>
<li>Directory Format -&nbsp;/do/&lt;folder&gt;/&lt;action&gt;.php</li>
</ul>
<p>A do-response is used to get anything back that isn't nothing, or a view. For example, an api script may return JSON headers and content, thus being a&nbsp;<em>response</em>. Response Do's reside in /do/&lt;folder&gt;/&lt;action&gt; and can be access from a browser by using the following URL format:/do/response/&lt;actionGroup&gt;/&lt;action&gt;, where actionGroup = a folder in /do/ appropriately named for the actions within it and &lt;action&gt; is the name of the php file within /do/&lt;actionGroup&gt;/ (without the .php).</p>
<p>For example, you might have an api that has two functions, returning data and returning meta-data. You might organise your do files into /do/api/data.php and /do/api/meta-data.php. To call these scripts, you would browse to /do/response/api/data (or meta-data).&nbsp;</p>
<p>Inside a /do/&lt;actionFolder&gt;/&lt;action&gt; script file, you have access to all the constants setup in the config. This file is included under the /library/Lectric/controller.class.php construct function.&nbsp;</p>
<p><strong>Do response actions scripts also accept GET params, but must test for existence of GET / POST vars.</strong></p>
<h5>Do&nbsp;Action</h5>
<ul>
<li>URL Format - /do/action/&lt;namespace&gt;/&lt;className&gt;/&lt;functionName&gt;</li>
<li>Directory Format -&nbsp;/library/&lt;namespace&gt;/&lt;className&gt;.class.php</li>
</ul>
<p>A do-action is a way to run a specific class function (inventively called a "do" function). These functions are called if the URL falls into the format of /do/action/&lt;namespace&gt;/&lt;className&gt;/&lt;functionName&gt;. You would run a do-action if you wanted to return a view or nothing only.</p>
<p>Classes that have to run do functions must:</p>
<ul>
<li>accept lecDBH as the first argument for the construct (if the class extends \Lectric\SQLQueryPDO, this will be the case anyway. otherwise, make sure you set your additional argument defaults!)</li>
<li>reside in the folder asked for by the URL nodes</li>
</ul>
<p>Do functions must:</p>
<ul>
<li>be called do_&lt;functionName&gt;</li>
<li>be a public function</li>
<li>accept arguments of ($_POST, and $_GET) (or none, or just $_POST)</li>
<li>have a return type of&nbsp;\Lectric\controlAction and return said object</li>
</ul>
<p>The&nbsp;\Lectric\controlAction is used to determine what happens once the do function has been run. The construct can be passed the arguments of type, param1, param2 or no arguements to simply exit. For example, if after calling the do_function, you want the user to be redirected to "/", and providing you&nbsp;<strong>have not set any other headers</strong> then return&nbsp;new \Lectric\controlAction('view', '/'), optionally passing back a message in param2 to be saved to the controller session messages (see controller dox for details).</p>
<p>The example default Lectric project has examples of both do-responses and do-actions. See /view/default/js/js_home.php for do-response call and /do/lectric/test.php for a form that posts to a do-action (see /library/Lectric/lecDefault.class.php too!).</p>
<h2>Further reading</h2>
<p>&nbsp;All classes within Lectric have been docblocked, for you to generate documentation (I reccomend phpdox - it works fine with PHP 7.1.0 as of this release).&nbsp;</p>

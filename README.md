<h2>The light weight Lectric Framework!</h2>
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
<h4>Working Directory</h4>
<p>To start developing with Lectric after installation, it's as simple as cracking open the /view/default/ directory and modifying the files within!</p>
<p>It is recommended that a different default directory be used, by copying /view/default/ into another directory in /view/. Usually this would be /view/public/. This is to ensure that if you pull from this repo in the future, it doesn't overwrite any changes you've made to /view/default/. <strong>Ensure to define your default directory if not using /view/default/ in /engine/plugin/core_config.php - e.g. define('DEFAULT_DIRECTORY', 'public').</strong></p>
<h4><strong>Adding Pages</strong></h4>
<p>To add webpages for use in development, add them to &lt;working_directory&gt;_views in your database. These will be available to view on the app provided the URL matches against it using the following rules:</p>
<ul>
<li>The URL is split into nodes (see /library/Lectric/view.class.php) by the character "/"</li>
<li>The number of nodes and presence of a physical directory in /view/ determines:
<ul>
<li>The URL directory - the directory (if any) a user can see in the address bar</li>
<li>The FILE directory - the directory under /view/ to look for a render.php</li>
<li>The page URL - the end node of the URL</li>
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
<p>&nbsp;</p>

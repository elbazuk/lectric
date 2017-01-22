# lectric

<h1>The light weight Lectric Framework!</h1>

<p>To install:
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
        Note: You can use logic here to define both dev and production DB settings, by testing on $_SERVER['HTTP_HOST'] and providing different definitions!
    </li>
    <li>run install.sql to set up basic database tables</li>
    <li>browse to / and read the messages displayed!</li>
  <ul>
</p>
    

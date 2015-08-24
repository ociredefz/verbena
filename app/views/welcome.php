<section id="lite-container" class="content">
    <canvas id="cyberlite"></canvas>
    <div id="main">
        <header>
            <h1><?! Language::get('welcome.container-info') ?></h1>
            <h2><?! Language::get('welcome.container-heading') ?></h2>
        </header>
        <div class="spacer s-5"></div>
        <p><?! Language::get('welcome.container-message') ?></p>
        <p><?! Language::get('welcome.container-message-sub') ?></p>
        <div class="spacer s-10"></div>
        <pre class="version"><span class="blue-light">VERBENA</span> <span class="green-light">CURRENT RELEASE:</span> 2015.1-RC3</pre>
        <div class="version"><strong>NOTE:</strong> The following documentantion have not been updated with the recent changes.</div>

        <div class="centered-add-info"><a href="https://github.com/eurialo/verbena/blob/master/docs/CHANGELOG" target="_blank">Follow the Changelog in Github.</a></div>
        <div class="centered-add-info"><a class="btn light upper follow" href="https://github.com/eurialo/verbena" role="button" target="_blank"><?! Language::get('welcome.container-button') ?></a></div>
    </div>
</section>

<section id="index" class="content hidden">
    <div id="index-list-container">
        <button id="toggle" class="standard-width" type="button" data-toggle="collapse">
            <span class="toggle-line"></span>
            <span class="toggle-line"></span>
            <span class="toggle-line"></span>
        </button>

        <ul id="index-list" class="padded-list">
            <li><a href="#setting-environment" class="green-light">Set up the webserver environment</a>
                <ul class="padded-list">
                    <li><a class="blue-light sub-title">Configurate the webserver</a>
                        <ul class="padded-list">
                            <li><a href="#config-apache">Apache2</a></li>
                            <li><a href="#config-nginx">Nginx</a></li>
                            <li><a href="#config-verbena">vnserve</a></li>
                        </ul>
                    </li>
                    <li><a class="blue-light sub-title">Configurate the essential</a>
                        <ul class="padded-list">
                            <li><a href="#config-app">app.php</a></li>
                            <li><a href="#config-database">database.php</a></li>
                            <li><a href="#config-components">components.php</a></li>
                            <li><a href="#config-mail">mail.php</a></li>
                            <li><a href="#config-providers">providers.php</a></li>
                            <li><a href="#config-routes">routes.php</a></li>
                            <li><a href="#config-security">security.php</a></li>
                            <li><a href="#config-session">session.php</a></li>
                        </ul>
                    </li>
                    <li><a class="blue-light sub-title">Management of the assets</a>
                        <ul class="padded-list">
                            <li><a href="#config-gulpfile">Usage of gulpfile.js</a></li>
                            <li><a href="#config-vendor">Integrate a vendor</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#model-view-control" class="green-light">Controllers / Models / Views</a>
                <ul class="padded-list">
                    <li><a class="blue-light sub-title">Create an MVC base application</a>
                        <ul class="padded-list">
                            <li><a href="#example-controller">Controller</a></li>
                            <li><a href="#example-model">Model</a></li>
                            <li><a href="#example-view">View</a></li>
                        </ul>
                    </li>
                    <li><a class="blue-light sub-title">Template system</a>
                        <ul class="padded-list">
                            <li><a href="#template-system">How to use it</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#core-components-drivers" class="green-light">Core / Components / Drivers</a>
                <ul class="padded-list">
                    <li><a class="blue-light sub-title">Core Classes</a>
                        <ul class="padded-list">
                            <li><a href="#class-autoloader">Autoloader Class</a></li>
                            <li><a href="#class-environment">Environment Class</a></li>
                            <li><a href="#class-dispatcher">Dispatcher Class</a></li>
                            <li><a href="#class-controller">Controller Class</a></li>
                            <li><a href="#class-model">Model Class</a></li>
                            <li><a href="#class-view">View Class</a></li>
                        </ul>
                    </li>
                    <li><a class="blue-light sub-title">Component Classes</a>
                        <ul class="padded-list">
                            <li><a href="#class-language">Language Class</a></li>
                            <li><a href="#class-session">Session Class</a></li>
                            <li><a href="#class-security">Security Class</a></li>
                            <li><a href="#class-encrypt">Encrypt Class</a></li>
                            <li><a href="#class-html">HTML Class</a></li>
                            <li><a href="#class-mail">Mail Class</a></li>
                        </ul>
                    </li>
                    <li><a class="blue-light sub-title">Database Drivers</a>
                        <ul class="padded-list">
                            <li><a href="#class-factory">Factory Class</a></li>
                            <li><a href="#driver-instance">The <span class="green-light">$db</span> instance</a></li>
                            <li><a href="#driver-mongodb">MongoDB interaction</a></li>
                            <li><a href="#chaining-methods">Chaining Methods</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</section>

<section id="howto" class="content">
    <div class="container"> 
        <div class="row">
            <div class="rwc-12 mb sz-xs3">

                <header class="header-info">
                    <a name="setting-environment">
                        <h2 class="info-title">Set up the webserver environment</h2>
                    </a>
                    <a name="config-webserver">
                        <h3 class="info-sub-title">Configurate the webserver</h3>
                    </a>
                </header>

                <div class="section-info">
                    <a name="config-apache">
                        <h4 class="info-sub-thing info-tick">Apache2</h4>
                    </a>

                    <p>Below you can see an example of the configuration of virtualhost <strong>/etc/apache2/sites-available/[vh-name]</strong>: <br>
                        <small class="info-note-tick">Note:  you can use <strong>a2ensite</strong> to show the available virtualhosts in your webserver.</small></p>
                
<pre>&lt;<span class="blue-light">VirtualHost</span> *:80&gt;
    ServerName example.org

    ServerAdmin webmaster@example.org
    DocumentRoot <span class="green-light">/var/www/verbena</span>

    &lt;<span class="blue-light">Directory</span> <span class="green-light">/var/www/verbena/</span>&gt;
        Options Indexes FollowSymLinks MultiViews
        AllowOverride FileInfo
        Order allow,deny
        Allow from all
    &lt;/<span class="blue-light">Directory</span>&gt;

    ErrorLog <b>${APACHE_LOG_DIR}</b>/error.log
    CustomLog <b>${APACHE_LOG_DIR}</b>/access.log combined
&lt;/<span class="blue-light">VirtualHost</span>&gt;</pre>

                    <p class="info-paragraph">
                        The option <strong>Indexes</strong> means that a directory can be shown as list if no index page has been found. <br>
                        The option <strong>FollowSymLinks</strong> means that if a directory is a symbol link, follow the link. <br>
                        The option <strong>MultiViews</strong> content-negotiation, means that the server does an implicit filename pattern match. <br>
                    </p>

                    <p class="info-note-tick">Note:  the three options referred above are optional. The only one option that needs to make verbena work correctly is:</p>
                    <p class="info-paragraph">The <strong>AllowOVerride FileInfo</strong>. It allows the use of directives controlling document types, documents meta data and the modules rewrite/alias/action directives.</p>
                    <p>The remaining parameters: </p>
                    <p class="info-paragraph"><strong>Order allow,deny | Allow from | Deny from</strong> are limits of the access control and they refer to any means of controlling access to any resource.</p>

                    <a name="config-nginx">
                        <h4 class="info-sub-thing info-tick">Nginx</h4>
                    </a>

                    <p>Below you can see an example of the configuration of virtualhost <strong>/etc/nginx/sites-enabled/[vh-name]</strong>: <br>

<pre><span class="blue-light">server</span> {
    listen 80 default_server;

    root <span class="green-light">/var/www/verbena</span>;
    index <span class="green-light">index.php</span>;

    <span class="gray"># Enable mod rewrite like apache.</span>
    <span class="blue-light">location</span> / {
        <span class="blue-light">if</span> (!-e <b>$request_filename</b>) {
            rewrite ^/.*$ /<b>$1</b> last;
        }
    }

    <span class="gray"># Check the php files.</span>
    <span class="blue-light">location</span> ~ \.php$ {
        fastcgi_pass unix:<span class="green-light">/var/run/php5-fpm.sock</span>;
        include fastcgi_params;
    }

    <span class="gray"># Deny access to .htaccess files, if Apache's document root.</span>
    <span class="blue-light">location</span> ~ /\.ht {
        deny all;
    }
}</pre>

                    <a name="config-verbena">
                        <h4 class="info-sub-thing info-tick">vnserve &nbsp;(development environment)</h4>
                    </a>
                    <p>During the development you can use the alias <strong>vnserve</strong> of the PHP built-in webserver feature, but you must ensure that the <strong>base_path</strong> 
                        parameter in the <strong>app/config/app.php</strong> configuration file is empty, also if your project is located in a directory that is different from webroot.
                        Therefore no more changes are needed.</p>

                    <p>The following command is used to <strong>start</strong> the verbena webserver for development environment:</p>
<pre><span class="blue-light">deftcode</span> <span class="white">verbena</span> <span class="blue-light">$</span> php vnserve
[Sun Apr 19 3:59:38 2015] Starting webserver listening on localhost:8000</pre>

                    <p>If you want to <strong>bind</strong> the webserver to a <strong>distinct address</strong> and want to <strong>daemonizes</strong> a process, you can use: <br>
                        <small class="info-note-tick">Note:  the root permissions are needed to run a process that binds itself to a port minor to 1024.</small></p>

<pre><span class="blue-light">deftcode</span> <span class="white">verbena</span> <span class="blue-light">#</span> php vnserve verbena.local 80 daemon
[Sun Apr 19 3:59:38 2015] Starting webserver listening on verbena.local:80
<span class="blue-light">deftcode</span> <span class="white">verbena</span> <span class="blue-light">#</span></pre>
                </div>
                <!-- ./section-info -->

                <header class="header-info">
                    <a name="config-application">
                        <h3 class="info-sub-title">Configurate the essential</h3>
                    </a>
                </header>

                <div class="section-info">
                    <!-- app.php -->
                    <a name="config-app">
                        <h4 class="info-sub-thing info-tick">app.php</h4>
                    </a>

                    <p>In the app configuration file you can <strong>customize</strong> all the base options that are useful to verbena to <strong>set up</strong> 
                        the environment and to <strong>load correctly</strong> the contents that were <strong>generated</strong>.</p>

<pre>'environment'    <span class="blue-light">=></span>  'production',

<small class="info-note-tick"># Note: default value is set to production.</small></pre>

                    <p>The <strong>environment</strong> option is used to set the <strong>debug depht</strong>, the possible values are listed below:</p>
                    
                    <ul class="info-list-double info-list-double-rows-4">
                        <li><span>development</span></li>
                        <li>set application in development-mode (display debug messages, notices and errors).</li>
                        <li><span>debug</span></li>
                        <li>set application in debug-mode (display debug messages and errors).</li>
                        <li><span>quiet</span></li>
                        <li>set application in quiet-mode (disable the tracer and display only errors).</li>
                        <li><span>production</span></li>
                        <li>set application in production-mode (nothing).</li>
                    </ul>

                    <p>Don't forget to set to 'production' when the website is ready to go online.</p>

<pre>'base_path'    <span class="blue-light">=></span>  '',

<small class="info-note-tick"># Note: default value is empty.</small></pre>

                    <p>The <strong>base_path</strong> option is used to set the project <strong>sub/directory</strong> (if It exists) located in the webroot.
                        These are some syntax examples:</p>
                    
                    <ul class="info-list-single">
                        <li><span>/my-project/</span></li>
                        <li><span>/my-project/sub/</span></li>
                    </ul>

                    <small class="info-note-tick">Note:  the correct syntax contains the constraints slashes, don't forget to append and prepend them.</small>

<pre>'assets_path'    <span class="blue-light">=></span>  '/assets/',

<small class="info-note-tick"># Note: default value is set to /assets/.</small></pre>

                    <p>The <strong>assets_path</strong> option is used to set an <strong>alternative</strong> directory where locate the styles, scripts, images and fonts, 
                        the format of syntax is the same being used in base_path.</p>

<pre>'compress_output'    <span class="blue-light">=></span>  'false',

<small class="info-note-tick"># Note: default value is set to bool false</small></pre>

                    <p>The <strong>compress_output</strong> option is used to <strong>compress</strong> the HTML code before the page gets render, 
                        the accepted values are the known <strong>booleans true</strong> or <strong>false</strong></p>

<pre>'language'    <span class="blue-light">=></span>  'english',

<small class="info-note-tick"># Note: default value is set to english.</small></pre>

                    <p>The <strong>language</strong> option is used to set the <strong>global</strong> language of the framework, generally for the 
                        <strong>mixed messages</strong> such as <strong>errors</strong>, <strong>validations</strong> and so on..
                        To add a new language, you must create a new directory with the language name in <strong>app/language</strong>. <br>
                        <small class="info-note-tick">Note:  see how to use the multi-language in the verbena demo.</small></p>

<pre>'timezone'    <span class="blue-light">=></span>  'UTC',

<small class="info-note-tick"># Note: default value is set to UTC.</small></pre>

                    <p>The <strong>timezone</strong> option is used to set the <strong>time standard</strong> that will be used by the <a href="//php.net/manual/en/function.date.php" target="_blank">PHP date()</a> function,
                        you can see the available format directly in the external php documentation page <a href="//php.net/manual/en/timezones.php" target="_blank">List of supported Timezones</a>.</p>



                    <!-- database.php -->
                    <a name="config-database">
                        <h4 class="info-sub-thing info-tick">database.php</h4>
                    </a>

                    <p>In this configuration file you can set which <strong>driver</strong> will be used by the models to the <strong>interaction with database</strong>. 
                        At this moment, verbena <strong>supports fourth</strong> different types of <strong>databases</strong>, 
                        <strong>three</strong> are <strong>Relational Databases</strong> and one is a <strong>NoSQL Database</strong>.</p>

                    <ul class="info-list-double info-list-double-rows-4">
                        <li><span>MySQL</span></li>
                        <li>the driver for the famous relational MySQL database.</li>
                        <li><span>PostgreSQL</span></li>
                        <li>the driver for another famous relational database PostgreSQL.</li>
                        <li><span>SQLite</span></li>
                        <li>the driver for one of the biggest and more used relational database SQLite.</li>
                        <li><span>MongoDB</span></li>
                        <li>the driver for the one of the most known NoSQL database MongoDB.</li>
                    </ul>

                    <p>Leave empty to disable the database handler. <br>
                        <small class="info-note-tick">Note:  disabling the database handler will speed up the internal loader.</small>
                    </p>



                    <!-- components.php -->
                    <a name="config-components">
                        <h4 class="info-sub-thing info-tick">components.php</h4>
                    </a>

                    <p>The components configuration file is used by the <strong>autoloader</strong> to <strong>generate</strong> the <strong>namespace aliases</strong> and then <strong>preloads</strong> them, 
                        this is also useful when you use a <strong>shorthand static call</strong> in the views, so you don't need to <strong>declare</strong> the namespaces into the views every time.</p>

                    <p>If you want to <strong>extend</strong> the verbena <strong>framework</strong>, don't forget to <strong>append</strong> your custom class in the <strong>aliases list</strong>, <br>
                        in the end of the aliases array in this file, after that, verbena does the rest. <br><br>
                        <small class="info-note-tick">Note:  see the <a href="#extending-verbena">Extending Verbena</a> section to learn how to extend the framework up to your choice.</small></p>



                    <!-- mail.php -->
                    <a name="config-mail">
                        <h4 class="info-sub-thing info-tick">mail.php</h4>
                    </a>

                    <p>The mail configuration file allows you to configure the mail server that will be used by verbena to send emails. <br>
                        At this moment verbena only supports the SMTP transport with a custom integrated send mail system and the <a href="//php.net/manual/en/function.date.php" target="_blank">PHP mail()</a> function.</p>

                    <p>By default, verbena is configured to send mail through a local mail service such as Postfix or Sendmail, 
                        so if you want to use a local service you must install and configure the postfix or sendmail daemon as service.</p>

<pre>'driver'      <span class="blue-light">=></span>  'smtp',

<small class="info-note-tick"># Note: default value is set to SMTP.</small></pre>

                    <p><strong>Don't change</strong> the value of driver because at the moment this is the <strong>only</strong> one <strong>supported</strong> by verbena.</p>

<pre>'hostname'    <span class="blue-light">=></span>  'localhost',
'port'        <span class="blue-light">=></span>  25,

<small class="info-note-tick"># Note: default value is set to localhost.</small></pre>

                    <p>Set the <strong>hostname</strong> or <strong>ip address</strong> and <strong>port</strong> of the mail server you want to use.</p>

<pre>'timeout'     <span class="blue-light">=></span>  10,

<small class="info-note-tick"># Note: default value is set to 10.</small></pre>

                    <p>Set the <strong>timeout</strong> of the socket that will be created by the <a href="//php.net/manual/en/function.fsockopen.php" target="_blank">PHP fsockopen()</a>.</p>

<pre>'auth'        <span class="blue-light">=></span>  false,

<small class="info-note-tick"># Note: default value is set to boolean false.</small></pre>

                    <p>Set to <strong>true</strong> if you want to <strong>enable</strong> the <strong>SMTP authentication</strong>.</p>

<pre>'username'    <span class="blue-light">=></span>  '',
'password'    <span class="blue-light">=></span>  '',

<small class="info-note-tick"># Note: default value is set to empty</small></pre>

                    <p>Set the <strong>username</strong> and <strong>password</strong> that will be used to perform the <strong>SMTP authentication</strong>. <br>
                        <small class="info-note-tick">Note:  these values will be ignored if the '<strong>auth</strong>' parameter is set to <strong>boolean false</strong>.</small></p>

<pre>'def_mail_address' <span class="blue-light">=></span>  'user@example.org',
'def_mail_name'    <span class="blue-light">=></span>  'User'

<small class="info-note-tick"># Note: default value is set to an example user.</small></pre>

                    <p>Set the <strong>sender name</strong> and <strong>email address</strong>, you can <strong>change</strong> this values by <strong>calling statically</strong> the 
                        <strong>Mail::add_from(name, address)</strong> method.</p>

<pre>'html'     <span class="blue-light">=></span>  true,

<small class="info-note-tick"># Note: default value is set to true.</small></pre>

                    <p>Specify if the format of the email is HTML.</p>

<pre>'add_header_params'    <span class="blue-light">=></span> [
    'X-Mailer'  => 'PHP/' . PHP_VERSION
]

<small class="info-note-tick"># Note: default mailer type is set to the php version.</small></pre>

                    <p>Add your custom header parameters to the email skeleton.</p>

<p><small class="info-note-tick">Note: if the <strong>email</strong> is in the <strong>HTML format</strong>, the <strong>MIME-Version</strong> and <strong>Content-Type</strong> 
    will be <strong>automatically append</strong> to the <strong>header</strong> by the module but you can <strong>override them</strong> by <strong>add new</strong> values to 
    the <strong>add_header_params</strong>.</small></p>

                    <!-- providers.php -->
                    <a name="config-providers">
                        <h4 class="info-sub-thing info-tick">providers.php</h4>
                    </a>

                    <p>Providers are part of the <strong>template system</strong> provided by verbena, here you can set the <strong>endpoints</strong> of the <strong>external CDNs</strong> 
                        (<strong>Content Delivery Network</strong>). The template system will be explained further on.</p>

                    <p>These are the <strong>default CDN</strong> links supported by verbena:</p>

<pre><span class="gray">/**
 *  CDN script endopoints.
 */</span>
'provider_scripts'  <span class="blue-light">=></span>  [
    'jquery'        <span class="blue-light">=></span>  '//code.jquery.com/jquery-1.11.2.min.js',
    'jquery2'       <span class="blue-light">=></span>  '//code.jquery.com/jquery-2.1.3.min.js',
    'jquery-ui'     <span class="blue-light">=></span>  '//code.jquery.com/ui/1.11.4/jquery-ui.min.js',
    'angularjs'     <span class="blue-light">=></span>  '//code.angularjs.org/snapshot/angular.min.js',
    'socket-io'     <span class="blue-light">=></span>  '//cdn.socket.io/socket.io-1.3.4.js',
    'bootstrap'     <span class="blue-light">=></span>  '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js'
],

<span class="gray">/**
 *  CDN stylesheet endopoints.
 */</span>
'provider_styles'   <span class="blue-light">=></span>  
    'jquery-ui'     <span class="blue-light">=></span>  '//code.jquery.com/ui/1.11.4/themes/dot-luv/jquery-ui.css',
    'bootstrap'     <span class="blue-light">=></span>  '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'
]

<small class="info-note-tick">Note: you can adds more CDN links by appending to the current arrays.</small>
<small class="info-note-tick">Note: the usage of double-slashes (//) is used for the compatibility of website, 
        Eg. if your site is running under HTTPS, the CDNs will be loaded using https and viceversa.</small></pre>
                    
                    <p>As you can see, the supported CDNs for <strong>scripts</strong> are <strong>jQuery/UI</strong>, <strong>AngularJS</strong>, <strong>Socket.IO</strong> and 
                        <strong>Twitter Bootstrap</strong> whilst the supported <strong>cascade style sheets CDNs</strong> are <strong>jQuery</strong> and <strong>AngularJS</strong>.</p>
                    <p>The usage of the providers will be explained in the <a href="#template-system">Template System</a> section.</p>




                    <!-- routes.php -->
                    <a name="config-routes">
                        <h4 class="info-sub-thing info-tick">routes.php</h4>
                    </a>

                    <p>The route list will be used to <strong>instruct</strong> the framework on how the <strong>requests</strong> need to be <strong>handled</strong>.</p>

<pre><span class="blue-light">Dispatcher</span>::route('/', 'welcome.index', null, <span class="blue-light">Dispatcher</span>::HTTP_REQUEST_GET);</pre>

                    <p>By analyzing the above route call we can say:</p>

                    <ul class="info-list-single">
                        <li>The first parameter is reserved to the URI request path string;</li>
                        <li>The second parameter is reserved to indicate which controller and method will handle the request;</li>
                        <li>The third parameter is reserved to the filter (not yet implemented, so set it to null);</li>
                        <li>The fourth parameter is reserved to the HTTP request type, (for now, the framework supports only the GET, POST requests);</li>
                    </ul>

                    <p>Another small example could be:</p>

<pre><span class="blue-light">Dispatcher</span>::route('404', 'welcome.notfound');</pre>

                    <p>High above, you are <strong>telling</strong> the <strong>framework</strong> which <strong>behavior</strong> must have when a <strong>page was not found</strong>. <br>
                        Unlike as mentioned high above, the '<strong>404</strong>' here, is the view file to be <strong>loaded</strong>, whilst the '<strong>welcome.notfound</strong>' is the same as above, 
                        where '<strong>welcome</strong>' is the <strong>controller name</strong> and '<strong>notfound</strong>' is the <strong>controller method</strong> to call.</p>

                    <p>Another example you must know is the usage of <strong>anonymous function</strong>:</p>

<pre><span class="blue-light">Dispatcher</span>::route('/eg-anon-method', 
    <span class="blue-light">function</span>(<b>$arg_first</b> <span class="blue-light">=</span> null, <b>$arg_last</b> <span class="blue-light">=</span> null) {

        <span class="blue-light">echo</span> 'Anonymous function called with arguments: ' . <b>$arg_first</b> . ' : ' . <b>$arg_last</b>;

    },  null,
<span class="blue-light">Dispatcher</span>::HTTP_REQUEST_GET);</pre>

                    <p>So.. now if you try to get '<strong>/eg-anon-method/1/2</strong>' the dispatcher will try to call the anonymous function that is inside the route rule and print the string 
                        '<strong>The anonymous function has been called with arguments: 1 : 2</strong>'.</p>



                    <!-- security.php -->
                    <a name="config-security">
                        <h4 class="info-sub-thing info-tick">security.php</h4>
                    </a>

                    <p>There are the <strong>directives</strong> about <strong>security</strong> that can be enabled in the framework.</p>

<pre>'x-frame-options'    <span class="blue-light">=></span>  'SAMEORIGIN',

<small class="info-note-tick"># Note: default value is set to SAMEORIGIN.</small></pre>

                    <p>The <strong>X-Frame-Options</strong> tells the browser that does not allows the other sites to display your page inside an iframe. <br>
                        This is a <strong>protection</strong> against the <strong>Clickjacking attacks</strong>.</p>

                    <p>Possible values are:</p>
                    
                    <ul class="info-list-double info-list-double-rows-3">
                        <li><span>DENY</span></li>
                        <li>the page cannot be displayed in a frame, regardless of the site attempting to do so.</li>
                        <li><span>SAMEORIGIN:</span></li>
                        <li>the page can only be displayed in a frame on the same origin as the page itself.</li>
                        <li><span>ALLOW-FROM uri:</span></li>
                        <li>the page can only be displayed in a frame on the specified origin.</li>
                    </ul>

                    <p>To disable the <strong>X-Frame-Options</strong> in the response, set it to <strong>boolean false</strong></p>

<pre>'x-powered-by'       <span class="blue-light">=></span>  false,

<small class="info-note-tick"># Note: default value is set to boolean false.</small></pre>

                    <p>The <strong>X-Powered-By</strong> tells the browser to not display the <strong>PHP version</strong> information in the response, 
                        this is useful to <strong>prevent gathering information</strong> by an attacker.</p>
                    
                    <p>To disable the <strong>X-Powered-By</strong> in the response, set it to <strong>boolean false</strong></p>

<pre>'content-security-policy'            <span class="blue-light">=></span>  true,
'content-security-policy-allowed'    <span class="blue-light">=></span>  [
    'default-src'                 <span class="blue-light">=></span>  [],
    'script-src'                  <span class="blue-light">=></span>  [
        'verbena.local',
        "'unsafe-inline'",
        "'unsafe-eval'"
    ],
    'object-src'                  <span class="blue-light">=></span>  [],
    'style-src'                   <span class="blue-light">=></span>  [
        'verbena.local',
        'fonts.googleapis.com',
        "'unsafe-inline'"
    ],
    'img-src'                     <span class="blue-light">=></span>  [],
    'media-src'                   <span class="blue-light">=></span>  [],
    'frame-src'                   <span class="blue-light">=></span>  [],
    'font-src'                    <span class="blue-light">=></span>  [
        'verbena.local',
        'fonts.gstatic.com',
         'fonts.googleapis.com'
    ],
    'connect-src'                 <span class="blue-light">=></span>  [],
    'form-action'                 <span class="blue-light">=></span>  [],
    'sandbox'                     <span class="blue-light">=></span>  [],
    'script-nonce'                <span class="blue-light">=></span>  [],
    'plugin-types'                <span class="blue-light">=></span>  [],
    'reflect-xss'                 <span class="blue-light">=></span>  [],
    'report-uri'                  <span class="blue-light">=></span>  []
],

<small class="info-note-tick"># Note: default value of 'content-security-policy' is set to boolean true.</small></pre>

                    <p>The <strong>Content-Security-Policy</strong> (CSP) is an <strong>W3C specification</strong> offering the possbility to <strong>instruct</strong> 
                        the client browser from which <strong>location</strong> and/or which type of <strong>resources</strong> are <strong>allowed</strong> to be <strong>loaded</strong>.</p>
                    
                    <p>To disable the <strong>Content-Security-Policy</strong> in the response, set it to <strong>boolean false</strong> <br>
                        <small class="info-note-tick">Note:  the values of '<strong>content-security-policy-allowed</strong>' will be ignored if the '<strong>content-security-policy</strong>' 
                            parameter is set to <strong>boolean false</strong>.</small></p>

<pre>'http-strict-transport-security'    <span class="blue-light">=></span>  false,

<small class="info-note-tick"># Note: default value is set to boolean false.</small></pre>

                    <p>The <strong>HTTP-Strict-Transport-Security</strong> (HSTS) is an opt-in <strong>security enhancement</strong> that is specified by a web application through the 
                        <strong>use</strong> of a <strong>special response</strong> header. Once a supported browser receives this header that <strong>browser</strong> will 
                        <strong>prevent any communication</strong> from being sent over <strong>HTTP</strong> to the specified domain and will <strong>send all</strong> 
                        communication over <strong>HTTPS</strong> instead. It also <strong>prevents HTTPS click</strong> through <strong>prompts</strong> on <strong>browsers</strong>.</p>

                    <p>An example <strong>scenario</strong> of <strong>HSTS</strong> could be:<p>
                    <p class="marginer">The first time a <strong>user visits</strong> your site, the <strong>browser</strong> will <strong>store this header</strong>. 
                        If the user <strong>later visits</strong> your site <strong>again</strong>, maybe using an <strong>unsafe WLAN</strong> connection, the browser 
                        <strong>remembers</strong> to <strong>call</strong> it back exclusively with <strong>HTTPS</strong>.<br>
                        This a <strong>protection</strong> for the <strong>sslstrip</strong> sniffing.</p>

                    <p>To enable the <strong>HTTP-Strict-Transport-Security</strong> in the response, set it to <strong>boolean true</strong>. <br>
                        <small class="info-note-tick">Note:  HTST enabled requires the HTTPS support in the webserver therefore in the website.</small></p>

                    <p>Another security enhancement is the <strong>Cross-Origin Resource Sharing</strong> (CORS) that is a mechanism that allows <strong>restricted resources</strong> 
                        (e.g. fonts, javascript, etc.) on a web page to be <strong>requested from another domain</strong> outside the domain from which the resource originated.</p>

<pre>'cross-origin-resource-sharing'            <span class="blue-light">=></span>  [
    'access-control-allow-origin'       <span class="blue-light">=></span> [
        'http://verbena.deftcode.ninja'
    ],
    'access-control-expose-headers'     <span class="blue-light">=></span> [],
    'access-control-max-age'            <span class="blue-light">=></span> [
        10
    ],
    'access-control-allow-credentials'  <span class="blue-light">=></span> [
        'true'
    ],
    'access-control-allow-methods'      <span class="blue-light">=></span> [
        'GET', 'POST'
    ]
],

<small class="info-note-tick"># Note: the fields above are the default-set.</small></pre>

                    <p>Finally, verbena <strong>allows</strong> you to <strong>enter custom</strong> header <strong>parameters</strong> at your choice that will be 
                        <strong>included</strong> in each <strong>HTTP response</strong>.</p>

<pre>'custom-headers'    <span class="blue-light">=></span>  [
    'X-VERBENA'     <span class="blue-light">=></span>  '2015.1',
]

<small class="info-note-tick"># Note: default value is set to an example verbena version.</small></pre>



                    <!-- session.php -->
                    <a name="config-session">
                        <h4 class="info-sub-thing info-tick">session.php</h4>
                    </a>

                    <p>This configuration file includes the needed parameters to <strong>generate</strong> the client <strong>session cookie</strong> and also some parameters 
                        <strong>needed</strong> by the <strong>Security class</strong> method like <strong>encrypt</strong> and <strong>decrypt</strong>.</p>

<pre>'encryption_key'         <span class="blue-light">=></span>  '0d9c31effbcfc94288106d2acb809faa',
'encryption_key_salt'    <span class="blue-light">=></span>  '1gGfBn0u45JhIl8z4JVy7lJ2EqRnVVFV6kLR7oCoRxHnKaQpGF2tLaKa6aLnLmCp',

<small class="info-note-tick"># Note: default values are set to custom MD5/SHA-256 hashes.</small></pre>

                    <p>The <strong>encryption_key</strong> and <strong>encryption_key_salt</strong> are used in <strong>two-ways</strong>, the first will be used for 
                        <a href="//php.net/manual/en/function.hash-hmac.php" target="_blank">PHP hash_hmac()</a> function if <strong>cookie encryption</strong> was set 
                        to <strong>boolean true</strong> and it also will be used <strong>toghether</strong> with <strong>encryption_key_salt</strong> by the security helper functions 
                        <strong>Security::encrypt(string, key)</strong> and <strong>Security::decrypt(string, key)</strong> that use internal 
                        <a href="//php.net/manual/en/function.hash.php" target="_blank">PHP hash()</a> function to <strong>generate</strong> a <strong>sha-256</strong> hash string.</p>

                    <p>Therefore, when starting <strong>configuring</strong> this <strong>section</strong> don't forget to <strong>generate hard hashes</strong> with more 
                        <strong>entropy</strong> as possible. <br>
                        <small class="info-note-tick">Note:  for security reasons, don't leave empty these lines.</small></p>

<pre>'name'    <span class="blue-light">=></span>  'vn_session',

<small class="info-note-tick"># Note: default value is set to 'vn_session'.</small></pre>

                    <p>The <strong>name</strong> identifies the name of the <strong>cookie</strong>.</p>

<pre>'path'    <span class="blue-light">=></span>  '/',

<small class="info-note-tick"># Note: default value is set to '/'.</small></pre>

                    <p>The <strong>path</strong> indicates the path of the cookie (in which <strong>uri path</strong> the <strong>cookie</strong> should be <strong>used</strong>). <br>
                        <small class="info-note-tick">Note:  the <strong>slash</strong> (/) indicates the <strong>webroot</strong> of website, therefore the cookie is <strong>valid</strong> 
                        for <strong>all</strong> website <strong>paths</strong>.</small></p>

<pre>'domain'    <span class="blue-light">=></span>  false,

<small class="info-note-tick"># Note: default value is set to boolean false.</small></pre>

                    <p>The <strong>domain</strong> indicates the domain of the cookie (in which <strong>domain name</strong> the cookie should be <strong>activated</strong>). <br>
                        If <strong>set</strong> to <strong>false</strong>, the <strong>framework automatically</strong> gets the local <strong>domain</strong> name and <strong>sets it for you</strong></p>

<pre>'lifetime'    <span class="blue-light">=></span>  2678400,

<small class="info-note-tick"># Note: default value is set to integer ~2678400 (1 Month).</small></pre>

                    <p>The <strong>lifetime</strong> indicates the life-time of the cookie, or better the <strong>duration</strong> of the <strong>cookie</strong>.</p>

<pre>'expire_on_close'    <span class="blue-light">=></span>  false,

<small class="info-note-tick"># Note: default value is set to boolean false.</small></pre>

                    <p>The <strong>expire_on_close</strong> indicates if the cookie <strong>expires</strong> when the <strong>browser</strong> gets <strong>closed</strong> (this ignores the life-time).</p>

<pre>'secure'    <span class="blue-light">=></span>  false,

<small class="info-note-tick"># Note: default value is set to boolean false.</small></pre>

                    <p>The <strong>secure</strong> indicates if the <strong>cookie</strong> should be <strong>passed</strong> through an <strong>HTTPS connection</strong>.</p>

<pre>'encrypt'    <span class="blue-light">=></span>  true,

<small class="info-note-tick"># Note: default value is set to boolean true.</small></pre>

                    <p>The <strong>encrypt</strong> indicates if the cookie should be <strong>encrypted</strong> by the <a href="//php.net/manual/en/function.hash-hmac.php" target="_blank">PHP hash_hmac()</a> 
                        function using the <strong>encryption_key</strong>.</p>

<pre>'httponly'    <span class="blue-light">=></span>  true,

<small class="info-note-tick"># Note: default value is set to boolean true.</small></pre>

                    <p>The <strong>httponly</strong> indicates if the cookie must have the <strong>httpOnly</strong> <strong>flag</strong> set to.</p>
                </div>
                <!-- ./section-info -->



                <!-- Management of the assets -->
                <header class="header-info">
                    <a name="assets-and-gulpfile">
                        <h3 class="info-sub-title">Management of the assets</h3>
                    </a>
                </header>

                <div class="section-info">
                    <!-- Usage of gulpfile.js -->
                    <a name="config-gulpfile">
                        <h4 class="info-sub-thing info-tick">Usage of gulpfile.js</h4>
                    </a>

                    <p>The <strong>gulpfile</strong> helps you to <strong>generate</strong> the customs <strong>all-in-one scss</strong>, <strong>javascript</strong> and other <strong>vendor</strong> files.</p>
                    <p>During the <strong>development phase</strong> you must <strong>start</strong> the <strong>gulp</strong> in order to <strong>watch</strong> the <strong>realtime modifications</strong> 
                        so, <br>if you make a <strong>change</strong>, a <strong>new css</strong> or <strong>javascript</strong> file will be <strong>generated</strong> and <strong>minified</strong>.</p>

<pre><span class="blue-light">deftcode</span> <span class="white">verbena</span> <span class="blue-light">$</span> gulp watch
[03:55:10] Using gulpfile /var/www/verbena/gulpfile.js
[03:55:10] Starting 'watch'...
[03:55:10] Finished 'watch' after 6.17 ms</pre>

                    <p>The high above example, <strong>starts</strong> the <strong>gulp</strong> in the <strong>listen-mode</strong> and <strong>waits</strong> for a <strong>change</strong>.</p>
                    <p><small class="info-note-tick"># Note: by default you have some examples of scss, javascript and font-awesome integrations.</small></pre></p>

                    <!-- Integrate a vendor -->
                    <a name="config-vendor">
                        <h4 class="info-sub-thing info-tick">Integrate a vendor</h4>
                    </a>

                    <p>If you want to <strong>add</strong> a new <strong>third-party vendor</strong> files, you must <strong>store</strong> these files to the <strong>assets/vendor/</strong> 
                        path directory and the <strong>add</strong> some <strong>change</strong> to the <strong>gulpfile.js</strong></p>

<pre><span class="blue-light">var</span> path_stylesheets <span class="blue-light">=</span> './assets/stylesheets/',
    <span class="gray">// ...</span>
    path_fontawesome <span class="blue-light">=</span> './assets/vendor/font-awesome/fonts/';

gulp.<span class="blue-light">src</span>(path_fontawesome + '*').<span class="blue-light">pipe</span>(gulp.<span class="blue-light">dest</span>(path_fonts));</pre>

                    <p>The high above refers to the <strong>font-awesome integration</strong>, you can <strong>use It</strong> as <strong>example</strong> to <strong>integrate other vendor files</strong>.</p>
                </div>
                <!-- ./section-info -->



                <!-- CONTROLLERS / MODELS / VIEWS -->
                <header class="header-info">
                    <a name="model-view-control">
                        <h2 class="info-title">Controllers/Models/Views</h2>
                    </a>
                    <a name="example-application">
                        <h3 class="info-sub-title">Create an MVC base application</h3>
                    </a>
                </header>

                <div class="section-info">
                    <!-- Controller -->
                    <a name="example-controller">
                        <h4 class="info-sub-thing info-tick">Controller</h4>
                    </a>

                    <p>Create a new file in <strong>app/controllers/example.php</strong> and at first add the controllers <strong>namespace</strong>:</p>

<pre>&lt;?php

<span class="blue-light">namespace</span> App\Controllers;</pre>

                    <p>Now <strong>import</strong> the core controller <strong>namespace</strong> for our application:</p>

<pre><span class="blue-light">use</span> Bootstrap\Controllers\Controller;</pre>

                    <p>Create a <strong>class</strong> extended to the controller core class with a custom <strong>method</strong> named index():</p>

<pre><span class="blue-light">class</span> Example <span class="blue-light">extends</span> Controller {

    <span class="blue-light">public function</span> index() {

        <span class="blue-light">echo</span> 'Welcome to index page';

    }

}</pre>

                    <p>Our <strong>final controller</strong> class should be so:</p>

<pre>&lt;?php

<span class="blue-light">namespace</span> App\Controllers;

<span class="blue-light">use</span> Bootstrap\Controllers\Controller;

<span class="blue-light">class</span> Example <span class="blue-light">extends</span> Controller {

    <span class="blue-light">public function</span> index() {

        <span class="blue-light">echo</span> 'Welcome to index page';

    }

}</pre>

                    <p>Now you need to <strong>add</strong> a <strong>route rule</strong> into the <strong>app/config/routes.php</strong> configuration file like this:</p>

<pre><span class="blue-light">Dispatcher</span>::route('/', 'example.index', null, <span class="blue-light">Dispatcher</span>::HTTP_REQUEST_GET);</pre>



                    <!-- Model -->
                    <a name="example-model">
                        <h4 class="info-sub-thing info-tick">Model</h4>
                    </a>

                    <p>See the <a href="#database-drivers">Database Drivers</a> section to learn how to interact with the database drivers.</p>

                    <p>The first step consist to select the database driver you want to use in your application in <strong>app/config/database.php</strong> configuration file.
                        In this example app we are going to use the <strong>MySQL</strong> database driver.</p>

<pre>'driver'    <span class="blue-light">=></span>  'mysql',</pre>

                    <p>Creates a new file like <strong>app/models/user.php</strong> and at first add the models <strong>namespace</strong>:</p>

<pre>&lt;?php

<span class="blue-light">namespace</span> App\Models;</pre>

                    <p>Now <strong>import</strong> the needed <strong>namespaces</strong> for our application like what you do in the controller step:</p>

<pre><span class="blue-light">use</span> Bootstrap\Models\Model;
<span class="blue-light">use</span> Bootstrap\Database\Factory;</pre>

                    <p>Creates a <strong>class</strong> extended with the model core class with a custom <strong>method</strong> named <strong>get_users()</strong> 
                        that interacts with the selected driver.</p>
                
                    <p>You have <strong>two-ways</strong> to interact with database <strong>driver</strong>, the first is by accessing the database driver <strong>statically</strong> 
                        through the <strong>Factory class</strong>, the second is by interfacing to the <strong>database instance</strong> directly.</p>
                
                    <p>The example below explains how you can <strong>interact</strong> to the database driver by accessing it <strong>statically</strong> with a <strong>Factory class</strong>:</p>

<pre><span class="blue-light">class</span> User <span class="blue-light">extends</span> Model {

    <span class="blue-light">public static function</span> get_users() {

        <span class="blue-light">Factory</span>::query('<span class="green-light">SELECT</span> first_name, last_name <span class="green-light">FROM</span> users',  <span class="blue-light">Factory</span>::QUERY_WAIT);
        <span class="blue-light">Factory</span>::execute();

        <b>$result</b> <span class="blue-light">= Factory</span>::fetch('object');

        <span class="blue-light">if</span> (!empty(<b>$result</b>)) {
            <span class="blue-light">return</span> <b>$result</b>;
        }

    }

}</pre>

                    <p>Whilst this one explain how to <strong>access</strong> to the database driver by accessing it through the <strong>database instance</strong>:</p>

<pre><span class="blue-light">class</span> User <span class="blue-light">extends</span> Model {

    <span class="blue-light">protected static</span> <b>$db</b>;

    <span class="blue-light">public static function</span> get_users() {

        <span class="blue-light">self</span>::<b>$db</b> <span class="blue-light">= Factory</span>::<b>$db</b>;

        <span class="blue-light">if</span> (<span class="blue-light">self</span>::<b>$db</b>) {
            <b>$query</b> <span class="blue-light">= self</span>::<b>$db</b><span class="blue-light">-></span>prepare('<span class="green-light">SELECT</span> first_name, last_name <span class="green-light">FROM</span> users');
            <b>$query</b><span class="blue-light">-></span>execute();

            <b>$result</b> <span class="blue-light">=</span> <b>$query</b><span class="blue-light">-></span>fetchObject();

            <span class="blue-light">if</span> (!empty(<b>$result</b>)) {
                <span class="blue-light">return</span> <b>$result</b>;
            }
        }

    }

}</pre>

                    <p>Using the first method, our <strong>final model</strong> class should be so:</p>

<pre>&lt;?php

<span class="blue-light">namespace</span> App\Models;

<span class="blue-light">use</span> Bootstrap\Models\Model;
<span class="blue-light">use</span> Bootstrap\Database\Factory;

<span class="blue-light">class</span> User <span class="blue-light">extends</span> Model {

    <span class="blue-light">public static function</span> get_users() {

        <span class="blue-light">Factory</span>::query('<span class="green-light">SELECT</span> first_name, last_name <span class="green-light">FROM</span> users',  <span class="blue-light">Factory</span>::QUERY_WAIT);
        <span class="blue-light">Factory</span>::execute();

        <b>$result</b> <span class="blue-light">= Factory</span>::fetch('object');

        <span class="blue-light">if</span> (!empty(<b>$result</b>)) {
            <span class="blue-light">return</span> <b>$result</b>;
        }

    }

}</pre>

                    <p>At this point, you just need to <strong>update</strong> your <strong>controller</strong> by <strong>importing</strong> the model <strong>namespace</strong> 
                        and after by <strong>replacing</strong> the function '<strong>echo</strong>' with this:</p>

<pre>&lt;?php

<span class="blue-light">namespace</span> App\Controllers;

<span class="blue-light">use</span> Bootstrap\Controllers\Controller;
<span class="blue-light">use</span> App\Models\User;

<span class="blue-light">class</span> Example <span class="blue-light">extends</span> Controller {

    <span class="blue-light">public function</span> index() {

        <b>$data</b> <span class="blue-light">= User</span>::get_users();

    }

}</pre>

                    <p>Since you have learned how to <strong>create</strong> a base <strong>model</strong> to interact with database, you can proceed by learning on how <strong>integrate</strong> an 
                        example <strong>view</strong> to display the <strong>data</strong> we have <strong>retrieved</strong> by the model..</p>


                    <!-- View -->
                    <a name="example-view">
                        <h4 class="info-sub-thing info-tick">View</h4>
                    </a>

                    <p>In this part, you learn how to <strong>create</strong> a base <strong>view</strong> file to display the <strong>data</strong> we have <strong>retrieved</strong> in previous section.</p>
                    <p>Take a look into the <strong>app/views/</strong> directory, as you can see there are <strong>two directories</strong> called '<strong>includes</strong>' and '<strong>layouts</strong>', 
                        they <strong>exist</strong> because verbena has an <strong>integrated</strong> template system (see <a href="#template-system">Template System</a> section for full references)
                        that <strong>allows</strong> you to <strong>create clean</strong>, <strong>lightweight</strong> and <strong>slender view</strong> files.</p>

                    <p><small class="info-note-tick">Note:  the <strong>template system</strong> is similar to the <strong>Liquid Template Language</strong> used by 
                        <strong>Jekyll</strong> and other software.</small></p>

                    <p>Ok.. you can start by <strong>creating</strong> a base layout file that <strong>contains</strong> the <strong>skeleton</strong> of the <strong>code</strong> like this:</p>

<pre>&lt;!DOCTYPE html&gt;
&lt;<span class="blue-light">html</span>&gt;
&#91;% <span class="green-light">include</span> head %&#93;
&lt;<span class="blue-light">body</span>&gt;
    &lt;<span class="blue-light">div id</span>="wrapper"&gt;
        &#91;% <span class="green-light">include</span> header %&#93;

        &#91;% <span class="green-light">content</span> %&#93;

        &#91;% <span class="green-light">include</span> footer %&#93;
    &lt;/<span class="blue-light">div</span>&gt;
&lt;/<span class="blue-light">body</span>&gt;
&lt;/<span class="blue-light">html</span>&gt;</pre>

                    <p>Save the example above in the <strong>app/views/layouts/</strong> directory.</p>
                    <p>Now you can start by <strong>creating</strong> the needs '<strong>includes</strong>' files that we have <strong>included</strong> in our <strong>skeleton</strong> 
                        such as '<strong>head.inc</strong>', '<strong>header.inc</strong>' and finally '<strong>footer.inc</strong>':</p>

<pre><span class="gray">&lt;-- head.inc --&gt;</span>
&lt;<span class="blue-light">head</span>&gt;
    &lt;<span class="blue-light">meta charset</span>="utf-8"&gt;
    &lt;<span class="blue-light">meta http-equiv</span>="X-UA-Compatible" <span class="blue-light">content</span>="IE=edge"&gt;
    &lt;<span class="blue-light">meta name</span>="viewport" <span class="blue-light">content</span>="width=device-width, initial-scale=1"&gt;

    &lt;<span class="blue-light">title</span>&gt;Example Web Page&lt;/<span class="blue-light">title</span>&gt;
    
    &#91;% <span class="green-light">stylesheet</span> app %&#93;
&lt;/<span class="blue-light">head</span>&gt;</pre>

<pre><span class="gray">&lt;-- header.inc --&gt;</span>
&lt;<span class="blue-light">header</span>&gt;
    &lt;<span class="blue-light">h1</span>&gt;Example Web Page!&lt;/<span class="blue-light">h1</span>&gt;
&lt;/<span class="blue-light">header</span>&gt;</pre>

<pre><span class="gray">&lt;-- footer.inc --&gt;</span>
&lt;<span class="blue-light">footer</span>&gt;
    Example footer text
&lt;/<span class="blue-light">footer</span>&gt;</pre>


                    <p>After that, create the view '<strong>example_view</strong>' of your page that will be replaced by the template system in the skeleton at the line that contains &#91;% content %&#93; and
                        store it as <strong>app/views/example_view.php</strong>:</p>

<pre><span class="gray">&lt;-- example_view.php --&gt;</span>
&lt;<span class="blue-light">div</span>&gt;
    &lt;<span class="blue-light">ul</span>&gt;
    &lt;?php
        <span class="blue-light">if</span> (!empty(<b>$data</b>)) {
            <span class="blue-light">foreach</span> (<b>$data</b> as <b>$key</b> <span class="blue-light">=></span> <b>$value</b>) {
                <span class="blue-light">echo</span> '&lt;<span class="blue-light">li</span>&gt;' . <b>$value</b><span class="blue-light">-></span>first_name . ' : ' . <b>$value</b><span class="blue-light">-></span>last_name . '&lt;/<span class="blue-light">li</span>&gt;';
            }
        }
    ?&gt;
    &lt;/<span class="blue-light">ul</span>&gt;
&lt;/<span class="blue-light">div</span>&gt;</pre>


                    <p>If you have completed all the previously listed steps, now you need to <strong>update</strong> the <strong>controller</strong> by 
                        <strong>importing</strong> the <strong>View namespace</strong> and <strong>rendering</strong> the <strong>view</strong> through the method 
                        <strong>View::visualize(view_name, layout_name, data)</strong>.</p>
                
                    <p>The <strong>final controller</strong> may be look like this:</p> 

<pre>&lt;?php

<span class="blue-light">namespace</span> App\Controllers;

<span class="blue-light">use</span> Bootstrap\Controllers\Controller;
<span class="blue-light">use</span> Bootstrap\Views\View;

<span class="blue-light">use</span> App\Models\User;

<span class="blue-light">class</span> Example <span class="blue-light">extends</span> Controller {

    <span class="blue-light">public function</span> index() {

        <b>$data</b> <span class="blue-light">= User</span>::get_users();
        <span class="blue-light">View</span>::visualize('example_view', 'layout', <b>$data</b>);

    }

}</pre>

                    <p>Where the <strong>first argument</strong> of <strong>visualize</strong> is the <strong>name</strong> of our <strong>view file</strong>, the <strong>second</strong> 
                        is the <strong>name</strong> of our <strong>layout file</strong> and the <strong>third</strong> is the <strong>array data</strong> that contains the 
                        <strong>database data</strong> we have retrieved previously.</p>

                    <p>Your <strong>final application</strong> has a <strong>tree</strong> like this:</p>
<pre><span class="green-light">.</span>/<span class="green-light">verbena-project</span>
|── <span class="blue-light">app</span>
|   |── <span class="blue-light">config</span>
|   |   └── ...
|   |── <span class="blue-light">controllers</span>
|   |   └── <span class="green-light">example.php</span>
|   |── <span class="blue-light">language</span>
|   |   └── ...
|   |── <span class="blue-light">models</span>
|   |   └── <span class="green-light">user.php</span>
|   |── <span class="blue-light">storage</span>
|   |   └── ...
|   |__ <span class="blue-light">views</span>
|       |── <span class="blue-light">includes</span>
|       |   ├── <span class="green-light">footer.inc</span>
|       |   ├── <span class="green-light">header.inc</span>
|       |   └── <span class="green-light">head.inc</span>
|       |── <span class="blue-light">layouts</span>
|       |   └── <span class="green-light">layout.inc</span>
|       |__ <span class="green-light">example_view.php</span>
|── ...
|__ <span class="green-light">index.php</span>
</pre>
                    <p>Good, you have created your first MVC base application using verbena framework!</p>
                </div>
                <!-- ./section-info -->



                <!-- TEMPLATE SYSTEM -->
                <header class="header-info">
                    <a name="template-system">
                        <h3 class="info-sub-title">Template system</h3>
                    </a>
                </header>

                <div class="section-info">
                    <!-- app.php -->
                    <a name="template-system-usage">
                        <h4 class="info-sub-thing info-tick">How to use it</h4>
                    </a>

                    <p>The <strong>integrated template system</strong> is similar to the <strong>Liquid template system</strong> that is used by Jekyll and other software, it <strong>allows</strong> 
                        you to <strong>create</strong> a simple <strong>view scheme</strong> to <strong>render</strong> the web <strong>pages</strong> of your application. The examples below explain 
                        how to create a custom view scheme.</p>

                    <p>First of all, you must <strong>create</strong> the <strong>layout file</strong>. <br>
                        The <strong>layout</strong> file is <strong>main file</strong> of your <strong>view application</strong>, you can <strong>intend it</strong> as a <strong>skeleton</strong> of your <strong>view</strong>:</p>

<pre>&lt;!DOCTYPE html&gt;
&lt;<span class="blue-light">html</span>&gt;
&#91;% <span class="green-light">include</span> head %&#93;
&lt;<span class="blue-light">body</span>&gt;
    &lt;<span class="blue-light">div id</span>="wrapper"&gt;
        &#91;% <span class="green-light">include</span> header %&#93;

        &#91;% <span class="green-light">content</span> %&#93;

        &#91;% <span class="green-light">include</span> footer %&#93;
    &lt;/<span class="blue-light">div</span>&gt;
&lt;/<span class="blue-light">body</span>&gt;
&lt;/<span class="blue-light">html</span>&gt;</pre>

                    <p>As you can see in the example above, there are <strong>some keywords</strong> you can use to <strong>generate</strong> your <strong>view</strong> such as <strong>include</strong>,
                        <strong>content</strong> etc.. Here is the list of available keywords you can use in your view:</p>

                    <ul class="info-list-double info-list-double-rows-4">
                        <li><span>include</span></li>
                        <li>this keyword is used to include a file in other file, like the <a href="http://php.net/manual/en/function.include.php">PHP include statement</a></li>
                        <li><span>content</span></li>
                        <li>this is used to include the real content of view you want to display into a layout or a include file.</li>
                        <li><span>stylesheet</span></li>
                        <li>the keyword is used to generate the stylesheet links.</li>
                        <li><span>javascript</span></li>
                        <li>the keyword is used to generate the script links.</li>
                    </ul>

                    <p>In <strong>addition</strong> to the keywords that have been described, you can <strong>use</strong> some <strong>other keywords</strong> to <strong>facilitate</strong> the <strong>integration</strong> 
                        of <strong>thirdparty providers</strong> like <strong>jQuery</strong>, <strong>Twitter Boostrap</strong> and other:</p>

                    <p><small class="info-note-tick">Note:  you can extend the providers by editing the <strong>app/config/providers.php</strong> configuration file as you like.</small></p>

                    <ul class="info-list-double info-list-double-rows-2">
                        <li><span>provider-style</span></li>
                        <li>the keyword is used to generate the stylesheet links of thirdparty providers.</li>
                        <li><span>provider-script</span></li>
                        <li>the keyword is used to generate the script links of thirdparty providers.</li>
                    </ul>

                    <p>The examples above show you how to <strong>use</strong> the other just <strong>described keywords</strong>, for example <strong>after</strong> you have <strong>generated</strong> the 
                        <strong>skeleton layout file</strong>, you can <strong>proceed</strong> by <strong>generating</strong> the <strong>includes</strong> files you have declared with the <strong>templating keywords</strong>:</p>

<pre><span class="gray">&lt;-- head.inc --&gt;</span>
&lt;<span class="blue-light">head</span>&gt;
    &lt;<span class="blue-light">meta charset</span>="utf-8"&gt;
    &lt;<span class="blue-light">meta http-equiv</span>="X-UA-Compatible" <span class="blue-light">content</span>="IE=edge"&gt;
    &lt;<span class="blue-light">meta name</span>="viewport" <span class="blue-light">content</span>="width=device-width, initial-scale=1"&gt;

    &lt;<span class="blue-light">title</span>&gt;Welcome!&lt;/<span class="blue-light">title</span>&gt;
    
    &#91;% <span class="green-light">provider-style</span> bootstrap %&#93;
    &#91;% <span class="green-light">stylesheet</span> app %&#93;
&lt;/<span class="blue-light">head</span>&gt;</pre>

<pre><span class="gray">&lt;-- header.inc --&gt;</span>
&lt;<span class="blue-light">header</span>&gt;
    &lt;<span class="blue-light">h1</span>&gt;Welcome to my web page!&lt;/<span class="blue-light">h1</span>&gt;
&lt;/<span class="blue-light">header</span>&gt;</pre>

<pre><span class="gray">&lt;-- footer.inc --&gt;</span>
&lt;<span class="blue-light">footer</span>&gt;
    &#91;% <span class="green-light">provider-script</span> jquery %&#93;
    &#91;% <span class="green-light">provider-script</span> bootstrap %&#93;
    &#91;% <span class="green-light">javascript</span> app.min %&#93;
&lt;/<span class="blue-light">footer</span>&gt;</pre>

                    <p>So, the <strong>final view</strong> has the following <strong>structure</strong>:</p>

<pre>&lt;<span class="blue-light">head</span>&gt;
    &lt;<span class="blue-light">meta charset</span>="utf-8"&gt;
    &lt;<span class="blue-light">meta http-equiv</span>="X-UA-Compatible" <span class="blue-light">content</span>="IE=edge"&gt;
    &lt;<span class="blue-light">meta name</span>="viewport" <span class="blue-light">content</span>="width=device-width, initial-scale=1"&gt;

    &lt;<span class="blue-light">title</span>&gt;Welcome!&lt;/<span class="blue-light">title</span>&gt;
    
    &lt;<span class="blue-light">link</span> <span class="blue-light">rel</span>="stylesheet" <span class="blue-light">href</span>="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css.css"&gt;
    &lt;<span class="blue-light">link</span> <span class="blue-light">rel</span>="stylesheet" <span class="blue-light">href</span>="/assets/stylesheets/app.css"&gt;
&lt;/<span class="blue-light">head</span>&gt;

&lt;<span class="blue-light">header</span>&gt;
    &lt;<span class="blue-light">h1</span>&gt;Welcome to my web page!&lt;/<span class="blue-light">h1</span>&gt;
&lt;/<span class="blue-light">header</span>&gt;

&lt;<span class="blue-light">footer</span>&gt;
    &lt;<span class="blue-light">script</span> <span class="blue-light">src</span>="//code.jquery.com/jquery-1.11.2.min.js"&gt;&lt;/<span class="blue-light">script</span>&gt;
    &lt;<span class="blue-light">script</span> <span class="blue-light">src</span>="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"&gt;&lt;/<span class="blue-light">script</span>&gt;
    &lt;<span class="blue-light">script</span> <span class="blue-light">src</span>="/assets/javascripts/app.min.js"&gt;&lt;/<span class="blue-light">script&gt;
&lt;/<span class="blue-light">footer</span>&gt;</pre>

                    <p>In <strong>addition</strong> to the described keywords, the view <strong>module offers</strong> you a <strong>shortcut</strong> to do a <strong>fast echo</strong>:</p>

<pre>&lt;?<span class="blue-light">!</span> 'print this string' ?&gt;</pre>

                    <p>That <strong>does</strong> the <strong>same thing</strong> of:</p>

<pre>&lt;? <span class="blue-light">echo</span> 'print this string' ?&gt;</pre>
                </div>
                <!-- ./section-info -->



                <!-- CORE / COMPONENTS / DRIVERS -->
                <header class="header-info">
                    <a name="core-components-drivers">
                        <h2 class="info-title">Core / Components / Drivers</h2>
                    </a>
                    <a name="core-classes">
                        <h3 class="info-sub-title">Core Classes</h3>
                    </a>
                </header>

                <div class="section-info">
                    <!-- Autoloader Class -->
                    <a name="class-autoloader">
                        <h4 class="info-sub-thing info-tick">Autoloader Class</h4>
                    </a>

                    <p>The <strong>autoloader</strong> class is delegated to <strong>perform</strong> the <strong>classes registration</strong> and <strong>shutdown</strong>, 
                        it's <strong>invoked</strong> after the preparation of the environment and only when it's <strong>ready</strong> to proceed the <strong>handling of requests</strong>.</p>
                    <p>The example below <strong>explains</strong> the <strong>steps</strong> that the framework <strong>does</strong> example when a <strong>request arrives</strong>:</p>

<pre><span class="blue-light">GET</span> /example HTTP/1.1</span>
<span class="blue-light">└──</span> <span class="green-light">index.php</span>
    <span class="blue-light">└──</span> <span class="green-light">bootstrap/autoload.php</span>
        <span class="blue-light">└──</span> <span class="green-light">bootstrap/environment/environment.php</span>
        <span class="blue-light">└──</span> <span class="green-light">bootstrap/autoloader/autoloader.php</span>
        <span class="blue-light">└──</span> <span class="green-light">bootstrap/components/session.php</span>
        <span class="blue-light">└──</span> <span class="green-light">bootstrap/dispatcher/dispatcher.php</span></pre>



                    <!-- Environment Class -->
                    <a name="class-environment">
                        <h4 class="info-sub-thing info-tick">Environment Class</h4>
                    </a>

                    <p>The <strong>environment</strong> class is the first one invoked by the autoload and it <strong>performs the tasks</strong> that are needed to <strong>prepare</strong> the 
                        <strong>environment</strong> (such as the <strong>loading</strong> of the <strong>configuration files</strong> located at <strong>app/config/*</strong>) that will be 
                        <strong>used</strong> by the <strong>other classes</strong>.</p>

                    <p>This class offers two methods to <strong>set</strong> or <strong>get</strong> the environment variables such as the variables stored in the <strong>app/config/*.php</strong>
                        configuration files. The example below shows you how to set or get the environment variables:</p>


<pre><span class="gray">// Import the namespace</span>
<span class="blue-light">use</span> Bootstrap\Components\Language;


<span class="gray">// Set an environment key-value</span>
<span class="blue-light">Environment</span>::set_env('my-key', 'my-value')


<span class="gray">// Get a value by key</span>
<span class="blue-light">Environment</span>::get_env('my-key')
<span class="gray">// Return</span>
my-value


<span class="gray">// Get all environment variables</span>
<span class="blue-light">Environment</span>::get_env()
<span class="gray">// Return</span>
Array
(
    [abs]    <span class="blue-light">=></span> <span class="green-light">/var/www/verbena</span>
    [config] <span class="blue-light">=></span> Array
        (
            [app]        <span class="blue-light">=></span> <span class="green-light">/var/www/verbena/app/config/app.php</span>
            [routes]     <span class="blue-light">=></span> <span class="green-light">/var/www/verbena/app/config/routes.php</span>
            [session]    <span class="blue-light">=></span> <span class="green-light">/var/www/verbena/app/config/session.php</span>
            [database]   <span class="blue-light">=></span> <span class="green-light">/var/www/verbena/app/config/database.php</span>
            [providers]  <span class="blue-light">=></span> <span class="green-light">/var/www/verbena/app/config/providers.php</span>
            [components] <span class="blue-light">=></span> <span class="green-light">/var/www/verbena/app/config/components.php</span>
            [security]   <span class="blue-light">=></span> <span class="green-light">/var/www/verbena/app/config/security.php</span>
            [mail]       <span class="blue-light">=></span> <span class="green-light">/var/www/verbena/app/config/mail.php</span>
        )
    ...</pre>



                    <!-- Dispatcher Class -->
                    <a name="class-dispatcher">
                        <h4 class="info-sub-thing info-tick">Dispatcher Class</h4>
                    </a>

                    <p>The <strong>dispatcher</strong> class does the <strong>main work</strong> of the <strong>request handling</strong>, when it's called by the environment class. <br> 
                        In the first moment It loads the routes rules and when the loading have been completed it <strong>parses</strong> the <strong>request and directs</strong> the request 
                        if a route rule was found in the previously loaded rules</p>

                    <p>Assuming that the <strong>router rule</strong> was <strong>found</strong>, the <strong>dispatcher</strong> tries to <strong>load the controller</strong> 
                        (always by the router rule) and then <strong>renders the view</strong> (if the <strong>rendering method exists</strong> on the controller/method).</p>



                    <!-- Controller Class -->
                    <a name="class-controller">
                        <h4 class="info-sub-thing info-tick">Controller Class</h4>
                    </a>

                    <p>The controller method assumes the <strong>role of a father</strong> (or <strong>library</strong>), in fact when a dispatcher loads the controller, 
                        <strong>each controller</strong> needs to be <strong>extended</strong> to <strong>this</strong>.
                        This is <strong>useful</strong> when <strong>extending the framework</strong>, maybe if you want to <strong>apply</strong> some <strong>method</strong> 
                        or something else to <strong>all</strong> the application <strong>controllers</strong>.</p>



                    <!-- Model Class -->
                    <a name="class-model">
                        <h4 class="info-sub-thing info-tick">Model Class</h4>
                    </a>

                    <p>The model class <strong>has</strong> the <strong>same things</strong> that have been described in the <strong>controller</strong>.</p>




                    <!-- View Class -->
                    <a name="class-view">
                        <h4 class="info-sub-thing info-tick">View Class</h4>
                    </a>

                    <p>Yes, also the view class <strong>has</strong> the <strong>same things</strong> of the <strong>controller</strong> just as the model.</p>
                </div>
                <!-- ./section-info -->


                <header class="header-info">
                    <a name="config-application">
                        <h3 class="info-sub-title">Component Classes</h3>
                    </a>
                </header>

                <div class="section-info">
                    <!-- Language Class -->
                    <a name="class-language">
                        <h4 class="info-sub-thing info-tick">Language Class</h4>
                    </a>

                    <p>The <strong>language</strong> class allows you to <strong>manage</strong> the <strong>languages</strong> of your website, by <strong>default</strong> the framework is 
                        configured with the <strong>english</strong> and <strong>italian</strong> languages but you can <strong>easily extend it</strong>. 
                        So we can start by <strong>explain</strong> the <strong>language methods</strong> and how to <strong>use them</strong> with some examples:</p>

<pre><span class="blue-light">use</span> Bootstrap\Components\Language;</pre>

                    <p>First <strong>import</strong> the <strong>language namespace</strong> component.</p>

<pre><span class="blue-light">Language</span>::get_active_language()</pre>

                    <p>The example above is used to <strong>get</strong> the current <strong>active</strong> session <strong>language</strong>. <br>
                        The method returns the boolean <strong>false</strong> if session <strong>language</strong> is <strong>not set</strong>.</p>

<pre><span class="blue-light">Language</span>::set('english')</pre>

                    <p>The example above is used to <strong>set</strong> the session <strong>language</strong> to english. <br>
                        The method returns the boolean <strong>true</strong> on <strong>success</strong> or <strong>false</strong> on <strong>error</strong>.</p>

<pre><span class="blue-light">Language</span>::get('welcome.title')</pre>

                    <p>The example above is used to get the <strong>title</strong> language line from <strong>welcome</strong> file of the current active session language. <br>
                        The method returns the boolean value <strong>false</strong> on <strong>error</strong>. <br>
                        <small class="info-note-tick">Note:  if the <strong>session language</strong> field is <strong>empty</strong>, the <strong>fallback</strong> will be <strong>loaded</strong> 
                        (<strong>fallback</strong> means the <strong>language value</strong> in <strong>app/config/app.php</strong> that is <strong>default</strong> set to <strong>english</strong>).</small></p>

<pre><span class="blue-light">Language</span>::get('welcome.title', 'italian')</pre>

                    <p>Instead, the example above is used to get the <strong>title</strong> language line from <strong>specified language</strong>, where in this is case is <strong>italian</strong>. <br>
                        The method returns the boolean value <strong>false</strong> on <strong>error</strong>.</p>

                    <p>Now we know which methods exists in the language class and we can <strong>proceed</strong> by explain <strong>how to add</strong> a new custom <strong>language</strong> to your website. 
                        To start <strong>adding</strong> new <strong>language</strong> you must <strong>clone</strong> for example the <strong>english</strong> language to your 
                        <strong>new custom language</strong> with:</p>

<pre><span class="blue-light">deftcode</span> <span class="white">verbena</span> <span class="blue-light">$</span> cp -r app/language/english app/language/spanish</pre>

                    <p>After that, you can <strong>edit</strong> the <strong>files</strong> inside the <strong>cloned directory</strong> and <strong>translate</strong> the lines in 
                        <strong>spanish</strong> language.</p>
                    
                    <p>Now you can proceed by adding the router rule to <strong>app/config/routes.php</strong> such as:</p>

<pre><span class="blue-light">Dispatcher</span>::route('/language/set/spanish', 'welcome.language', null, <span class="blue-light">Dispatcher</span>::HTTP_REQUEST_GET);</pre>

                    <p><small class="info-note-tick">Note:  you can <strong>find</strong> the <strong>method language</strong> inside the <strong>bootstrap/controller/controller.php</strong> 
                        because it's a core method, <br>with that, you <strong>can use any controller name</strong> you prefers in the <strong>router rule</strong>
                        (remember that the <strong>welcome</strong> controller is <strong>extended</strong> to the <strong>core</strong> controller).</small></p>

                    <p>If the framework receives a <strong>GET</strong> request with <strong>path location /language/set/spanish</strong> it automatically switch the request to the 
                        controller <strong>welcome</strong> method <strong>language</strong> that is delegated to set the session language value.</p>

                    <p>There are <strong>two-methods</strong> for <strong>set</strong> session <strong>language</strong>, the language method accept two parameters:</p>


<pre><span class="blue-light">public static function</span> language(<b>$_language</b> <span class="blue-light">=</span> null, <b>$_redirect</b> <span class="blue-light">=</span> null) { <span class="green-light">...</span> }</pre>

                    <p>The <strong>first</strong> parameter <strong>$_language</strong> is the <strong>language name</strong> string and the <strong>last</strong> parameter 
                        <strong>indicates</strong> if you want to enable the <strong>redirect</strong> to the webroot after that the session language have been set.</p>

                    <p>If you want to <strong>disable</strong> the <strong>redirect</strong> to the webroot when the <strong>session</strong> have been <strong>set</strong>, you can do it with:</p>

<pre><span class="blue-light">Dispatcher</span>::route('/language/set/spanish/none', 'welcome.language', null, <span class="blue-light">Dispatcher</span>::HTTP_REQUEST_GET);</pre>

                    <p>As you can see, by <strong>adding any word</strong> you want to the <strong>end</strong> of the <strong>router rule</strong>, the <strong>redirect</strong> to the webroot 
                        will be <strong>disabled</strong>.</p>



                    <!-- Session Class -->
                    <a name="class-session">
                        <h4 class="info-sub-thing info-tick">Session Class</h4>
                    </a>

                    <p>The <strong>session</strong> class is used to <strong>manage</strong> the session <strong>parameters</strong> such as <strong>cookie</strong>, active 
                        <strong>language</strong> etc.. <br>
                        The <strong>examples</strong> below <strong>explain</strong> how to use the session <strong>methods</strong>.</p> 

<pre><span class="blue-light">use</span> Bootstrap\Components\Session;</pre>

                    <p>First <strong>import</strong> the <strong>session namespace</strong> component.</p>

<pre><span class="blue-light">Session</span>::set('key', 'value')</pre>

                    <p>The example above is used to <strong>set</strong> a <strong>session</strong> key => value. <br>
                        The method returns the boolean <strong>true</strong> on <strong>success</strong> or <strong>false</strong> on <strong>error</strong>.</p>

<pre><span class="blue-light">Session</span>::uset('key')</pre>

                    <p>The example above is used to <strong>unset</strong> the <strong>session</strong> value by key. <br>
                        The method returns the boolean <strong>true</strong> on <strong>success</strong> or <strong>false</strong> on <strong>error</strong>.</p>

<pre><span class="blue-light">Session</span>::get('key')</pre>

                    <p>The example above is used to <strong>get</strong> the <strong>session</strong> value by key. <br>
                        The method returns the boolean <strong>false</strong> on <strong>error</strong>.</p>

                    <p>For example, if you want to <strong>get</strong> the current <strong>session_id</strong> named <strong>vn_session</strong> by default, you can do it with:</p>

<pre><span class="blue-light">Session</span>::get('vn_session')

<span class="gray">// Return</span>
A6d8MSOwudFC%2BTWWlpBg0YFwRNyAM3K4zYuyg%2BSmPQDJ8UpJJvKGsfV1MaFGAnSf0yEr34WBivA7PKW551iFpLzskcozrC1SeRrvLs9OAT%2BI1AXW1h19R76EPibgWcWhgQF7GzlPlPkHg2Q%2FcHKbBryzQ1CoLYCmh386eoqM4JTKA%2FQbG%2F49TUs1DyUsd1lGijKYj3HSLvUM67%2FP%2Bn7dRA%3D%3D525772f78b534250263e5a0cf7ad68fa494a55f0</pre>



                    <!-- Security Class -->
                    <a name="class-security">
                        <h4 class="info-sub-thing info-tick">Security Class</h4>
                    </a>

                    <p>The <strong>security</strong> class contains all the <strong>methods</strong> you <strong>must use</strong> to <strong>prevent</strong> the <strong>attacks</strong> from malicious users.</p>

<pre><span class="blue-light">use</span> Bootstrap\Components\Security;</pre>

                    <p>First <strong>import</strong> the <strong>security namespace</strong> component.</p>

                    <p>Note that for now the class have only one method that mitigates the <strong>Cross-Site Scripting</strong> (XSS) <strong>attacks</strong>:</p>

<pre><span class="blue-light">Security</span>::filter_xss('some malicious string')</pre>

                    <p>For example, the test below explains how the function filters a malicious string:</p>

<pre><span class="blue-light">Security</span>::filter_xss('some &lt;<span class="blue-light">script</span>&gt;alert(1)&lt;/<span class="blue-light">script</span>&gt; injected alert script')

<span class="gray">// Return</span>
some alert(1) injected alert script</pre>

                    <p>Another great method for the XSS mitigation could be the <strong>Security::encode_tags(string)</strong> that is OWASP based and It's good to encode the HTML special characters. Here is an example sanitization of user input:</p>

<pre>&lt;<span class="blue-light">input</span> <span class="blue-light">type</span>='text' <span class="blue-light">name</span>='test' <span class="blue-light">value</span>='&lt;?! <span class="blue-light">Security</span>::encode_tags("' onclick='alert(1)"); ?&gt;'&gt;

<span class="gray">// Return</span>
&lt;<span class="blue-light">input</span> <span class="blue-light">type</span>="text" <span class="blue-light">name</span>="test" <span class="blue-light">value</span>="' onclick='alert(1)"&gt;
</pre>

                    <p>Or you can encode the special HTML characters like:</p>

<pre>&lt;?! <span class="blue-light">Security</span>::encode_tags("&lt;img src=x onerror=alert(1)&gt;"); ?&gt;

<span class="gray">// Return</span>
&amp;lt;img src=x onerror=alert(1)&amp;gt;
</pre>



                    <!-- Encrypt Class -->
                    <a name="class-encrypt">
                        <h4 class="info-sub-thing info-tick">Encrypt Class</h4>
                    </a>

                    <p>The <strong>encrypt</strong> class is used to <strong>encrypt</strong> or <strong>decrypt</strong> a string using <a href="http://php.net/manual/en/function.mcrypt-encrypt.php">PHP mcrypt_encrypt()</a> 
                        function in combinations with the encode <a href="http://php.net/manual/en/function.base64-encode.php">PHP base64_encode()</a> function.</p>

                    <p>The encrypt and decrypt functions uses by default the <strong>encryption_key</strong> and <strong>encryption_key_salt</strong> you have set in the <strong>app/config/session.php</strong> 
                        to get better encryption.</p>

<pre><span class="blue-light">use</span> Bootstrap\Components\Encrypt;</pre>

                    <p>You must start by <strong>importing</strong> the <strong>encrypt namespace</strong> component.</p>

<pre><span class="blue-light">Encrypt</span>::encrypt('a private string to be encrypted')

<span class="gray">// Return</span>
tzPdBVf5GPxZy7g/z2u/I2sdX5xHLEaL4VIeeNoATaU=</pre>

                    <p>The example above is used to <strong>encrypt</strong> a string.</p>

                    <p>Instead, the example below is used to <strong>decrypt</strong> a string that you previously have encrypted.</p>

<pre><span class="blue-light">Encrypt</span>::decrypt('tzPdBVf5GPxZy7g/z2u/I2sdX5xHLEaL4VIeeNoATaU=')

<span class="gray">// Return</span>
a private string to be encrypted</pre>

                    <p>If you want to use an <strong>encryption_key</strong> that is different from the key you have set in <strong>app/config/session.php</strong>, you must pass a second parameter to the 
                        <strong>Encrypt::encrypt(string, key)</strong> or <strong>Encrypt::decrypt(string, key)</strong> functions such as:</p>

<pre><span class="blue-light">Encrypt</span>::encrypt('a private string to be encrypted', 'another-encryption-key')

<span class="gray">// Return</span>
wbT3cweDjNBnbUR2zklVcy0M3UQ8ldX9+/gzPlxTzL0=

<span class="blue-light">Encrypt</span>::decrypt('wbT3cweDjNBnbUR2zklVcy0M3UQ8ldX9+/gzPlxTzL0=', 'another-encryption-key')

<span class="gray">// Return</span>
a private string to be encrypted</pre>



                    <!-- HTML Class -->
                    <a name="class-html">
                        <h4 class="info-sub-thing info-tick">HTML Class</h4>
                    </a>

                    <p>The <strong>HTML</strong> class is an helper class that allows you to generate a custom html code, the examples below explain how to use the helper methods.</p>

<pre><span class="blue-light">use</span> Bootstrap\Components\HTML;</pre>

                    <p>To use the class method, first <strong>import</strong> the <strong>HTML namespace</strong> component.</p>

                    <!-- HTML::url(internal-link) -->
<pre><span class="blue-light">HTML</span>::url('some-internal-link')

<span class="gray">// Return</span>
/my-website/some-internal-link</pre>

                    <p>The example above is used to <strong>generate</strong> a <strong>local url</strong> by <strong>adding</strong> the <strong>base_path</strong> 
                        (if It exists) to the specified <strong>link</strong>. <br>
                        Assuming the <strong>base_path</strong> is valorized to <strong>my-website</strong>.</p>


                    <!-- HTML::style(css-link, [attributes]) -->
<pre><span class="blue-light">HTML</span>::style('http://example.org/some.css')

<span class="gray">// Return</span>
&lt;<span class="blue-light">link</span> <span class="blue-light">rel</span>="stylesheet" <span class="blue-light">href</span>="http://example.org/some.css"&gt;


<span class="blue-light">HTML</span>::style('local.css')

<span class="gray">// Return</span>
&lt;<span class="blue-light">link</span> <span class="blue-light">rel</span>="stylesheet" <span class="blue-light">href</span>="/assets/stylesheets/some.css.css"&gt;</pre>

                    <p>For example, the style method used above is an helper to generate the <strong>&lt;link&gt; stylesheet</strong>.</p>
                    <p>If you want to <strong>add</strong> a custom <strong>attribute</strong> to the link tag, you must add a second parameter to the 
                        <strong>HTML::style(css-link, [attributes])</strong> method such as:</p>

<pre><span class="blue-light">HTML</span>::style('http://example.org/some.css', ['class' <span class="blue-light">=></span> 'example-class-name'])

<span class="gray">// Return</span>
&lt;<span class="blue-light">link</span> <span class="blue-light">rel</span>="stylesheet" <span class="blue-light">href</span>="http://example.org/some.css" <span class="blue-light">class</span>="example-class-name"&gt;</pre>


                    <!-- HTML::anchor(anchor-link, anchor-text, [attributes]) -->
                    <p>Another very helpful helper is the <strong>anchor</strong> method that allows you to generate the anchor-link tag <strong>&lt;a&gt;</strong>:</p>

<pre><span class="blue-light">HTML</span>::anchor('http://example.org', 'Example external link')

<span class="gray">// Return</span>
&lt;<span class="blue-light">a</span> <span class="blue-light">href</span>="http://example.org"&gt;Example external link&lt;/<span class="blue-light">a</span>&gt;


<span class="blue-light">HTML</span>::anchor('internal-link', 'Example internal link')

<span class="gray">// Return</span>
&lt;<span class="blue-light">a</span> <span class="blue-light">href</span>="/internal-link">Example internal link&lt;/<span class="blue-light">a</span>&gt;


<span class="blue-light">HTML</span>::anchor('http://example.org', 'Example external link with target blank', ['target' <span class="blue-light">=></span> '_blank'])

<span class="gray">// Return</span>
&lt;<span class="blue-light">a</span> <span class="blue-light">href</span>="http://example.org" <span class="blue-light">target</span>="_blank">Example external link with target blank&lt;/<span class="blue-light">a</span>&gt;</pre>


                    <!-- HTML::image(image-link, [attributes]) -->
                    <p>Instead, the example below explains how to use the <strong>image</strong> method to generate <strong>&lt;img&gt;</strong> links:</p>

<pre><span class="blue-light">HTML</span>::image('http://example.org/image.png')

<span class="gray">// Return</span>
&lt;<span class="blue-light">img</span> <span class="blue-light">src</span>="http://example.org/image.png"&gt;


<span class="blue-light">HTML</span>::image('internal-image.png')

<span class="gray">// Return</span>
&lt;<span class="blue-light">img</span> <span class="blue-light">src</span>="/assets/images/internal-image.png"&gt;


<span class="blue-light">HTML</span>::image('image-with-alt-attribute.png', ['alt' <span class="blue-light">=></span> 'Alternate image text'])

<span class="gray">// Return</span>
&lt;<span class="blue-light">img</span> <span class="blue-light">src</span>="/assets/images/image-with-alt-attribute.png" <span class="blue-light">alt</span>="Alternate image text"&gt;</pre>



                    <!-- Mail Class -->
                    <a name="class-mail">
                        <h4 class="info-sub-thing info-tick">Mail Class</h4>
                    </a>

                    <p>The <strong>mail</strong> class allows you to send emails, it's divided in two types, the first is an integrated system developed along with 
                        <a href="https://tools.ietf.org/html/rfc821">RFC-821 Simple Mail Transfer Protocol</a>. 
                        Instead the last uses the <a href="http://php.net/manual/en/function.mail.php">PHP mail()</a> function.</p>

                    <p>These steps show you how to send an example of mail. <br>
                        <small class="info-note-tick">Note:  don't forget to complete the configuration process in the file <strong>app/config/mail.php</strong>.</small></p>

<pre><span class="blue-light">use</span> Bootstrap\Components\Mail;</pre>

                    <p>First <strong>import</strong> the <strong>mail namespace</strong> component.</p>

<pre><span class="blue-light">Mail</span>::add_from('Your Name', 'sender@example.org')</pre>

                    <p>Then use the <strong>Mail::add_from(name, address)</strong> method to specify the <strong>sender name</strong> and <strong>mail address</strong>.</p>

<pre><span class="blue-light">Mail</span>::add_recipients('to@example.org')

<span class="blue-light">Mail</span>::add_recipients(['to@example.org', 'another@example.org'])</pre>

                    <p>Instead, the <strong>Mail::add_recipients(address|[addresses])</strong> method is used to add one or more recipients.</p>

<pre><span class="blue-light">Mail</span>::add_subject('Example mail subject')</pre>

                    <p>Use the <strong>Mail::add_subject(text-subject)</strong> to add the mail subject to your mail.</p>

<pre><span class="blue-light">Mail</span>::add_message('Example mail text body')</pre>

                    <p>And now add the mail text body through the <strong>Mail::add_mesage(text-message)</strong> method.</p>

<pre><span class="blue-light">Mail</span>::send()</pre>

                    <p>The final step consists in the call of <strong>Mail::send()</strong> that start the mail send process. <br>
                        The method returns the boolean <strong>false</strong> on failure, to retrieve the effective error message you can use the <strong>Mail::get_error()</strong> method.</p>

                    <p>For example, the test below show you how to <strong>test</strong> the success or failure of the <strong>send</strong> process and how to <strong>get</strong> 
                        the <strong>error message</strong>:</p>

<pre><span class="blue-light">if</span> (<span class="blue-light">Mail</span>::send() <span class="blue-light">===</span> false) {
    <span class="blue-light">echo Mail</span>::get_error();
}</pre>


                </div>
                <!-- ./section-info -->



                <!-- DATABASE DRIVERS -->
                <header class="header-info">
                    <a name="database-drivers">
                        <h3 class="info-sub-title">Database Drivers</h3>
                    </a>
                </header>

                <div class="section-info">
                    <!-- Factory Class -->
                    <a name="class-factory">
                        <h4 class="info-sub-thing info-tick">Factory Class</h4>
                    </a>

                    <p>The <strong>factory</strong> class is used to <strong>interact</strong> with the <strong>database drivers</strong>, it <strong>provides</strong> a lot of 
                        <strong>methods</strong> to do all the things you need to manage the database. <br>
                        <small class="info-note-tick">Note:  don't forget to <strong>complete</strong> the <strong>configuration process</strong> in the file 
                            <strong>app/config/database.php</strong>.</small></p>

                    <p>This example shows you an <strong>overview</strong> of what the <strong>factory</strong> class <strong>does</strong>:</p>

<pre><span class="green-light">Database Drivers</span>
└── <span class="blue-light">Factory</span>
    |
    |── query()
    |   └── <span class="blue-light">PDO driver class</span>
    |       └── query()
    |   └── <span class="blue-light">MongoDB driver class</span>
    |       └── query()
    |
    |── bind()
    |   └── <span class="blue-light">PDO driver class</span>
    |       └── bind()
    |   └── <span class="blue-light">MongoDB driver class</span>
    |       └── bind()
    |
    |__ <b>$db</b>
        └── <span class="green-light">Interface to the database instance</span>
            |
            |__ <span class="blue-light">PDO instance</span>
            |   └── prepare()
            |   └── execute()
            |   └── fetchObject()
            |
            |__ <span class="blue-light">MongoDB instance</span>
                └── listCollections()
</pre>

                    <p>As you can see, the <strong>factory</strong> class <strong>contains</strong> the <strong>custom methods</strong> for <strong>maintain</strong> 
                        the <strong>compatibility</strong> to <strong>all</strong> the <strong>drivers</strong>.</p>
                    
                    <p><small class="info-note-tick">Note:  the <strong>Interface to the database instance</strong> will be explained in the next section.</small></p>

                    <p>Well, we can <strong>start</strong> to <strong>explain</strong> these <strong>methods</strong> using as <strong>reference</strong> the <strong>MySQL driver</strong> 
                        with <strong>InnoDB engine</strong>:</p>

                    <!-- Factory::query() -->
<pre><span class="gray">// Example</span>
<span class="blue-light">Factory</span>::query('<span class="green-light">SELECT</span> column <span class="green-light">FROM</span> table', <span class="blue-light">Factory</span>::QUERY_DIRECT)


<span class="gray">// Example 2</span>
<span class="blue-light">Factory</span>::query('<span class="green-light">SELECT</span> column <span class="green-light">FROM</span> table',  <span class="blue-light">Factory</span>::QUERY_WAIT)</pre>

                    <p><small class="info-note-tick">Note:  the <strong>Factory::query(query-string, query-type)</strong> can be <strong>understood</strong> as a <strong>kind</strong> of 
                        <strong>Factory::prepare()</strong> method, as will be explained, all the behaviours <strong>depend</strong> on the <strong>query-type</strong> constant 
                        <strong>parameter</strong>.</small></p>

                    <p>There are <strong>two-methods</strong> of <strong>usage</strong> in the example above, the <strong>first</strong> is by using the <strong>Factory::QUERY_DIRECT</strong>
                        that <strong>means</strong> (in the case of usage of PDO driver) the <strong>query doesn't need to wait</strong> the <strong>call</strong> of <strong>Factory::execute()</strong>
                        so you can <strong>fetch</strong> directly the <strong>data-object</strong>.</p>

                    <p class="info-note-tick"><strong>IMPORTANT:</strong> with <strong>direct query</strong>, you <strong>can't</strong> <strong>bind</strong> 
                        the <strong>parameters</strong> through the <strong>Factory::bind(bind-type, param, value, param-type)</strong>.</p>
                    
                    <p>Conversely, <strong>Factory::QUERY_WAIT</strong> means that you need to call the <strong>Factory::execute()</strong> before proceding fetching the <strong>data-object</strong>.</p>


                    <!-- Factory::bind() -->
<pre><b>$param_int</b>  <span class="blue-light">=</span> 88;
<b>$param_bool</b> <span class="blue-light">=</span> true;
<b>$param_null</b> <span class="blue-light">=</span> null;
<b>$param_str</b>  <span class="blue-light">=</span> 'Example string';
<b>$param</b>      <span class="blue-light">=</span> 123;


<span class="gray">// Example</span>
<span class="blue-light">Factory</span>::bind('param', '<span class="blue-light">:</span>param_int', <b>$param_int</b>, 'int')


<span class="gray">// Example 2</span>
<span class="blue-light">Factory</span>::bind('param', '<span class="blue-light">:</span>param_bool', <b>$param_bool</b>, 'bool')


<span class="gray">// Example 3</span>
<span class="blue-light">Factory</span>::bind('value', '<span class="blue-light">:</span>param_null', <b>$param_null</b>, 'null')


<span class="gray">// Example 4</span>
<span class="blue-light">Factory</span>::bind('value', '<span class="blue-light">:</span>param_str', <b>$param_str</b>, 'str')


<span class="gray">// Example 5 (Auto-check parameter type) 
// (in this case parameter is an integer type):</span>
<span class="blue-light">Factory</span>::bind('value', '<span class="blue-light">:</span>param', <b>$param</b>)</pre>

                    <p>The method <strong>Factory::bind(bind-type, param, value, param-type)</strong> is used to <strong>generate</strong> a <strong>secure query</strong> by <strong>binding</strong> 
                        the <strong>variables</strong> you need specifing the parameter type. As you can see, the method allows you to <strong>bind</strong> the <strong>parameter</strong> 
                        or <strong>bind</strong> the <strong>value</strong> by setting the first argument to '<strong>param</strong>' or '<strong>value</strong>'.</p>

<pre><span class="blue-light">Factory</span>::execute()</pre>

                    <p>The method <strong>Factory::execute()</strong> is used in correspondence with the method <strong>Factory::query(query-string, query-type)</strong>, and it just
                        <strong>performs</strong> the <strong>execution</strong> of the <strong>query</strong>.</p>

<pre><span class="gray">// Get single-row result</span>
<span class="blue-light">Factory</span>::fetch('object')


<span class="gray">// Get multiple-rows result</span>
<span class="blue-light">Factory</span>::mfetch('array')</pre>

                    <p>After the query execution, you can <strong>fetch</strong> the <strong>data-object</strong> by using the method above. The methods <strong>accepts one argument</strong>
                        that allows you to specify the <strong>fetch-type</strong>. The fetch-type could be <strong>array</strong> or <strong>object</strong> (object is default). <br>
                        You can use the <strong>Factory::fetch(fetch-type)</strong> method to fetch a single row or the <strong>Factory::mfetch(fetch-type)</strong> to fetch multiple rows.</p>

<pre><span class="blue-light">Factory</span>::affected_rows()</pre>

                    <p>Use the method above to <strong>retrieve</strong> the <strong>count</strong> of the <strong>affected rows</strong> of the <strong>last DELETE</strong>, <strong>UPDATE</strong> 
                        and other. For example if you try to <strong>delete something based</strong> on some <strong>condition</strong>, the method <strong>return how many rows</strong> that 
                        have been <strong>deleted</strong>.</p>

<pre><span class="blue-light">Factory</span>::inserted_id()</pre>

                    <p>Instead, in <strong>case</strong> of an <strong>INSERT</strong>, the method <strong>return</strong> the <strong>last inserted ID</strong>.</p>

<pre><span class="gray">// Prepare the query</span>
<span class="blue-light">Factory</span>::query('<span class="green-light">INSERT INTO</span> table (username, email) <span class="green-light">VALUES</span> (:username, :age)',  <span class="blue-light">Factory</span>::QUERY_WAIT)

<span class="gray">// Initiate a transaction</span>
<span class="blue-light">Factory</span>::transaction();

<span class="gray">// Bind the query parameters</span>
<span class="blue-light">Factory</span>::bind('param', '<span class="blue-light">:</span>username', 'eurialo', 'str')
<span class="blue-light">Factory</span>::bind('param', '<span class="blue-light">:</span>age', 26, 'int')

<span class="gray">// Executes the query</span>
<span class="blue-light">if</span> (<span class="blue-light">Factory</span>::execute() <span class="blue-light">===</span> false) {
    <span class="gray">// Rollback a transaction on query execution failure</span>
    <span class="blue-light">Factory</span>::rollback();
    <span class="blue-light">return</span> false;
}

<span class="gray">// Commits a transaction</span>
<span class="blue-light">Factory</span>::commit();</pre>

                    <p>In the example above, you can see the <strong>example</strong> usage of the <strong>transaction</strong> (in the <strong>case</strong> of you are <strong>using</strong> the 
                        <strong>InnoDB</strong> engine, because the transactions are supported only by it).</p>

<pre><span class="gray">// Debug the binded parameters</span>
<span class="blue-light">Factory</span>::params_dump()


<span class="gray">// Display the possible errors</span>
<span class="blue-light">Factory</span>::errors_dump()</pre>

                    <p>Finally, you can use the examples above to <strong>debug</strong> your <strong>database</strong> driver <strong>processes</strong> or <strong>display</strong> the possible 
                        <strong>errors</strong> you can <strong>encounter</strong> during the <strong>development</strong> of your application.</p>

                    <p>The examples below explain you how to debug your application and how to display the possibles errors:</p>

<pre><b>$username</b> <span class="blue-light">=</span> 'eurialo';

<span class="gray">// Prepare the query and bind the parameters</span>
<span class="blue-light">Factory</span>::query('<span class="green-light">SELECT</span> * <span class="green-light">FROM</span> users <span class="green-light">WHERE</span> username <span class="blue-light">= :</span>username',  <span class="blue-light">Factory</span>::QUERY_WAIT);
<span class="blue-light">Factory</span>::bind('param', '<span class="blue-light">:</span>username', <b>$username</b>);

<span class="gray">// Debug the binded parameters</span>
<span class="blue-light">echo</span> print_r(<span class="blue-light">Factory</span>::params_dump(), true);</pre>

<pre id="test"><span class="gray">// Prepare an example bogus query</span>
<b>$ret</b> <span class="blue-light">= Factory</span>::query('<span class="green-light">BOGUS SQL</span>',  <span class="blue-light">Factory</span>::QUERY_WAIT);


<span class="gray">// Debug the binded parameters</span>
<span class="blue-light">if</span> (<b>$ret</b> <span class="blue-light">===</span> false) {
    <b>$error</b> <span class="blue-light">=</span> Factory::errors_dump();

    <span class="blue-light">echo</span> print_r(<b>$error</b><span class="blue-light">-></span>errorInfo, true);
    <span class="gray">// Return</span>
    Array
    (
        [0] <span class="blue-light">=></span> 42000
        [1] <span class="blue-light">=></span> 1064
        [2] <span class="blue-light">=></span> You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'bogus sql' at line 1
    )

    <span class="blue-light">echo</span> print_r(<b>$error</b><span class="blue-light">-></span>getTrace(), true);
    <span class="gray">// Return</span>
    Array
    (
        [0] <span class="blue-light">=></span> Array
            (
                [file]      <span class="blue-light">=></span> <span class="green-light">/var/www/verbena/bootstrap/database/drivers/pdo_driver.php</span>
                [line]      <span class="blue-light">=></span> 85
                [function]  <span class="blue-light">=></span> prepare
                [class]     <span class="blue-light">=></span> PDO
                [type]      <span class="blue-light">=></span> <span class="blue-light">-></span>
                [args]      <span class="blue-light">=></span> Array
                    (
                        [0] <span class="blue-light">=></span> bogus sql
                    )

            )
        ...

    <span class="blue-light">echo</span> <b>$error</b><span class="blue-light">-></span>getMessage();
    <span class="gray">// Return</span>
    SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax...

    <span class="blue-light">echo</span> <b>$error</b><span class="blue-light">-></span>getCode();
    <span class="gray">// Return</span>
    42000

    <span class="blue-light">echo</span> <b>$error</b><span class="blue-light">-></span>getLine();
    <span class="gray">// Return</span>
    85

    <span class="blue-light">echo</span> <b>$error</b><span class="blue-light">-></span>getFile();
    <span class="gray">// Return</span>
    <span class="green-light">/var/www/verbena/bootstrap/database/drivers/pdo_driver.php</span>

}</pre>



                    <!-- PDO Class -->
                    <a name="driver-instance">
                        <h4 class="info-sub-thing info-tick">The <b>$db</b> instance</h4>
                    </a>

                    <p>Alternatively, you can avoid the usage of <strong>Factory</strong> class methods and <strong>accede</strong> to the <strong>database methods</strong> directly with the 
                        database <strong>instance</strong> that is available through the <strong>Factory::$db</strong> variable.</p>

                    <p>The example below shows you how to <strong>access</strong> these <strong>methods</strong> to <strong>retrieve</strong> some <strong>data-object</strong> always by using
                        the driver <strong>MySQL</strong> as an example:</p>

<pre><span class="gray">// Store the database instance</span>
<b>$db</b> <span class="blue-light">= Factory</span>::<b>$db</b>;

<span class="gray">// Check for valid instance</span>
<span class="blue-light">if</span> (<b>$db</b>) {
    <span class="gray">// Prepare the query and execute it</span>
    <b>$query</b> <span class="blue-light">=</span> <b>$db</b><span class="blue-light">-></span>prepare('<span class="green-light">SELECT</span> username, email <span class="green-light">FROM</span> users');
    <b>$query</b><span class="blue-light">-></span>execute();

    <span class="gray">// Retrieve the data-object</span>
    <b>$result</b> <span class="blue-light">=</span> <b>$query</b><span class="blue-light">-></span>fetchObject();

    <span class="gray">// Check for non-empty result and display it</span>
    <span class="blue-light">if</span> (!empty(<b>$result</b>)) {
        <span class="blue-light">return</span> <b>$result</b>;
    }
}</pre>



                    <!-- MongoDB Class -->
                    <a name="driver-mongodb">
                        <h4 class="info-sub-thing info-tick">MongoDB interaction</h4>
                    </a>

                    <p>This is an example interaction with the MongoDB driver using the $db instance:</p>

<pre><span class="gray">// Get the database instance</span>
<span class="blue-light">self</span>::<b>$db</b> = <span class="blue-light">Factory</span>::<b>$db</b>;

<span class="gray">// Return a collection</span>
<b>$db_admin</b> = <span class="blue-light">self</span>::<b>$db</b>->admin;
<span class="blue-light">return</span> <b>$db_admin</b>->listCollections();
</pre>



                    <!-- MongoDB Class -->
                    <a name="chaining-methods">
                        <h4 class="info-sub-thing info-tick">Chaining Methods</h4>
                    </a>

                    <p>Example usage:</p>

<pre><span class="gray">// Database insert</span>
<b>$return</b> = <span class="blue-light">Factory</span>::table('users')->insert([
    'username'  => 'eurialo',
    'email'     => 'eurialo@deftcode.ninja',
    'status'    => 0,
    'created'   => date('Y-m-d H:i:s'),
    'group_id'  => 1
]);

<span class="gray">// Database update</span>
<b>$return</b> = <span class="blue-light">Factory</span>::table('users')->where([
    'id'        => 1,
    'username'  => 'eurialo'
])->update([
    'email'     => 'eurialo@example.org',
    'status'    => 1
]);

<span class="gray">// Database delete</span>
<b>$return</b> = <span class="blue-light">Factory</span>::table('users')->where([
    'id'        => 2,
    'username'  => 'eurialo123'
])->delete();

<span class="gray">// Database count</span>
<b>$return</b> = <span class="blue-light">Factory</span>::table('users')->where([
    'status'    => 1,
    'username'  => 'eurialo123'
])->count();

<span class="gray">// Database select</span>
<b>$return</b> = <span class="blue-light">Factory</span>::table('users')->select([
    'username', 'email'
])->where([
    'email'     => 'eurialo@example.org',
    'status'    => 0
])->first();
</pre>

                </div>
                <!-- ./section-info -->

            </div>
        </div>
    </div>
</section>

Simple php framework with a single entry point

For enter to admin page list home page to end and click on "FistFramework".
Then "Да" and then login: admin, password: passw.
Now you can create or delete page.
Creating a page includes create file in app/controllers, app/models, app/view
and insert data to db.
After that you can modify these files.
if you created the data for the pages in the database manually and no created controller, model, view, 
they will be displayed in the list on the main page, but you will not be able to navigate to them.

All users who have access to the administration section in the "users" table of the database
there are entries: username, password, status (fields for email and email_status are not used yet).
Username and password can be anything (username field length is 25 characters, password is 255).
Status has three categories: admin, moder, user.
By default, there are no differences between them, they need to be set in the file app/view/adm.php by splitting
an array of pages into parts available for different categories of users. The example is in the file itself.
Statuses, respectively, can still be added.

This use namespase. 
classes are declared like this:
namespace App\Lib;
class Rout...


a class inside another class is called like this:
$r = new \App\Lib\Rout;


app/config/config.php:
constants declaration.


/first.oswc (index.php renamed in .htaccess):
include app/lib.php (load config.php and call to spl_autoload),
call to Rout and get into it site root path


app/lib/rout:
/**
*  App Rout class
* Parse URL and loads controller from app/controllers
* URL FORMAT may be /controller/method_for_controller_or_model/params/...?name=string&name2=string2
* if method not exists for controller - he may used as model method
* rout loaded controller and put other data from url to controllers input datas array
* where 'path' - a piece of url after /method/... before ? if exists method for controller
* or where 'path' - a piece of url after /controller/... before ? if method for controller not exists
* 'get_query' - after ?
* and may be 'post_query' - from html POST
* default loaded controller home with class Home and method index
*/


Controllers: its own for each page;

app/controllers/home:
method __construct load class View;
method index a gets name of class in which it is located,
and load method from model ($path -> from class Rout),
and load method generate from view ($content_view, $data, $template_view -> explained below).

app/controllers/adm - extends home:
protected string $template = TEMPLATEROOT.DS.'first/templ.php'; - the path to the template, 
it is possible to override it in child classes;
method index override parents method from Home,
load App\Lib\Let_adm_login which load simple authenticate class App\Lib/Auth,
which uses the class App\Lib\Session (methods for vars in php session). 
Adm controller set $_SESSION vars: 'user_name' for authentication and
'status' (admin, moder, user - readed from `users` db) for autorisation
in pages views class. 
Autorization is simple: for each status formed array of page alias and 
user will to see only this page.
Or you can check status in method of view class.
Also, the 'page_access' field for each page in the database table indicates the user who is allowed access.

Admins LOGIN and PASSWORD is in app/db/oswc.sqlite in table `users`,
default is 'login' => 'admin', 'pass' => 'passw;
password in db - "password" => password_hash("passw", PASSWORD_DEFAULT);
simple way to change it - generate in php script:
echo password_hash("your_new_passw", PASSWORD_DEFAULT);
and copy past to db table (use eg SQLiteStudio).

class Auth use trait Reject from App\Lib\Traits:
$this->reject_login() - it load header 502 Bad Gateway if password will be wrong enter 4 times


Models: its own for each page.


View: its own for each page, in app/view directory;

appp/lib/view.php - single View class,
(new View)->generate($content_view, $data, $template_view = TEMPLATEROOT.DS.'first/templ.php')
$content_view - from app/view (ready-to-output code),
$data - data array for model from controller (from rout),
$template_view - default template declare in method, you can specify your own,
or declare it in your controllers class:
protected string $template = TEMPLATEROOT.DS.'first/templ.php';
or rewrite it inside a controllers class method when calling the method generate from View class:
$this->view->generate('your_view.php', $vars_for_view, 'your_template_path.php');
or set templates in db tables "pages" in "page_templates" and View load it if your model get this var in $data array.


Work with database:

framework use Medoo (https://medoo.in);
write your database init class or use app/lib/db_init_sqlite.php as example
and rewrite it for your database type;
declare your database init class in config.php, then
$database = new DBINITNAME;
$database->db->select("users", "password", ["username" => $inp_login]);


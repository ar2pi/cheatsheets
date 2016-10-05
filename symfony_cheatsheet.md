# Symfony cheatsheet

### Create New Project:
```dos
php -r "readfile('https://symfony.com/installer');" > symfony
move symfony c:\wamp\www
cd wamp\www
php symfony new ProjectName 2.8.2
[OR] composer create-project symfony/framework-standard-edition ProjectName/ 2.8.2
```

### List available commands
```dos
php app\console
```

### Create New Bundle:
```dos
php app\console generate:bundle --namespace=Acme/TestBundle
```

## Routing

#### YAML file:

```yaml
# app/config/routing.yml
hello:
	path:	/hello/{firstName}/{lastName}
	defaults: { _controller: AppBundle:Hello:index }
```

## Controllers

#### A simple controller:

```php
// src/AppBundle/Controller/DefaultController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller; // to include the base Controller class and its helper methods / service objects
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SymfonyCheatSheetBundle:Default:index.html.twig');
    }

    public function contactAction(Request $request)
    {
        //get request variables.
        //do something, call service, go to database, create form, send emails, etc...
        return $this->render('SymfonyCheatSheetBundle:Default:feedback.html.twig', array([template vars]));
    }
}
```

```php
// src/AppBundle/Controller/HelloController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController extends Controller // <-- controller class
{
    public function indexAction($firstName, $lastName, Request $request) // <-- method
    {
        $page = $request->query->get('page', 1);
        if (!$page) {
            throw $this->ctreateNotFoundException('The page does not exist');
        }
        return new Response('<html><body>Hello '.$firstName.'!<br>Your last name is '.$lastName.'</body></html>');
    }
}
```

#### Working with the session
```php
public function sessionAction(Request $request)
{
    $session = $request->getSession();

    // store an attribuute for reuse during a later user request
    $session->set('foo', 'bar');
    // get the attribute set by another controller in another request
    $foobar = $session->get('foobar');
    // use a default value if the attribute doesn't exist
    $filters = $session->get('filters', array());
}
```

#### Store flash message
```php
$this->get('session')->getFlashBag()->add('notice','message');
```

#### Flash messages after form submit
```php
public function updateAction(Request $request)
{
    $form = this->createForm(...);
    $form->handleRequest($request);

    if ($form->isValid()) {

        $this->addFlash(
            'notice',
            'Your changes were saved!'
        );
        return $this->redirectToRoute('homepage', array(), 301);
    }
    return $this->render();
}
```

#### Forwarding to another controller
```php
public function forwardAction($firstName, $lastName)
{
    $response = $this->forward('AppBundle:Hello:index', array(
        'firstName' => $firstName,
        'lastName'	=> $lastName
    ));
    // ... further modify the response or return it directly
    return $response;
}
```

## Templating

#### Twig syntax
```twig
{# comentario #}
{{ mostrar_algo }}
{% hacer algo %}
```

#### Iterate in array
```twig
<ul>
  {% for usuario in usuarios %}
    <li>{{ usuario.nombreusuario | upper }}</li>
  {% else %}
    <li><em>no hay usuarios</em></li>
  {% endfor %}
</ul>
```

#### Extend template
```twig
{% extends 'MDWDemoBundle:Blog:articulo.html.twig' %}
```

#### Fetch block contents set in parent template
```twig
{% block javascripts %}
    {{ parent() }}
    {% javascripts '@AviaturPruebaBundle/Resources/public/js/prueba.js' %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}
{% endblock javascripts%}
```

#### Include template
```twig
{% include 'MDWDemoBundle:Blog:articuloDetalles.html.twig' with {'articulo': articulo} %}
```

#### Render controller output
```twig
{% render "MDWDemoBundle:Articulo:articulosRecientes" with {'max': 3} %}
```

#### Fetch assets located in assets folder
```twig
<img src="{{ asset('images/logo.gif') }}" alt="mi logo!" />
```

#### Variable handlers
```twig
{{ var | raw }} {# evita el escapado de variables #}
{{ var | escape }} {# fuerza el escapado de variables (opción por defecto en Symfony2) #}
{{ var | default('valor por defecto') }}

{% if variable is defined %}
    {# aplicar operaciones si no se ha declarado la variable #}
{% endif %}

{{ var | capitalize }} {# capitaliza el primer carácter de la cadena #}
{{ var | lower }} {# convierte a minúsculas la cadena #}
{{ var | upper }} {# convierte a mayúsculas la cadena #}
{{ var | title }} {# capitaliza cada palabra de la cadena #}

{{ var | date("m/d/Y", "Europe/Paris") }}

{{ 2500.333 | number_format(2, ',', '.') }}

{% filter upper %}
    Todo el texto de aquí será convertido a mayúsculas
{% endfilter %}
```

#### Set customized url link
```twig
{% set url_share = 'http://maycolalvarez.com' ~ path('blog_article', {
    'year'  : (article.created|date('Y')),
    'month' : (article.created|date('m')),
    'slug'  : article.slug })
%}
<!-- Coloca esta etiqueta donde quieras que se muestre el botón +1. -->
<g:plusone size="medium" href="{{ url_share }}"></g:plusone>
<a href="https://twitter.com/share" class="twitter-share-button" data-url="{{ url_share }}" data-lang="es">Twittear</a>
<div class="fb-like" data-href="{{ url_share }}" data-send="false" data-layout="button_count" data-width="100" data-show-faces="true"></div>
```

#### Display flash message if any
```twig
{% for flash_message in app.session.flashbag.get('notice') %}
    <div class="flash-notice">
        {{ flah_message}}
    </div>
{% endfor %}
```

## Doctrine

Dump data:
```php
\Doctrine\Common\Util\Debut::dump();
```

```yaml
// app\config\parameters.yml
database_driver = pdo_mysql
database_host = localhost
database_port =
database_name = blog
database_user = maestros
database_password = clavesecreta
```

Create database:
```dos
php app\console doctrine:database:create
```

Create entity (table object):
```dos
php app\console doctrine:generate:entity
```

Create the tables from created entities:
```dos
php app\console doctrine:schema:create
```

Reference Foreign Key:
```php
/**
* src/Mdw/BlogBundle/Entity/Comments.php
* @ORM\ManyToOne(targetEntity="Articles", inversedBy="comments")
* @ORM\JoinColumn(name="article_id", referencedColumnName="id")
* @return integer
*/
private $article;
public function setArticle(\Mdw\BlogBundle\Entity\Articles $article)
{
   $this->article = $article;
}

public function getArticle()
{
   return $this->article;
}
```
```
/**
* src/Mdw/BlogBundle/Entity/Articles.php
* @ORM\OneToMany(targetEntity="Comments", mappedBy="article")
*/
private $comments;
public function __construct()
{
   $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
}
public function addComments(\Mdw\BlogBundle\Entity\Comments $comments)
{
   $this->comments[] = $comments;
}

public function getComments()
{
   return $this->comments;
}
```

Drop tables:
```dos
php app\console doctrine:schema:drop --force
```

Updates tables (after altering Entity file for eg):
```dos
php app\console doctrine:schema:update --force
```

Check generated SQL:
```dos
php app\console doctrine:schema:drop --dump-sql
```

Get the Entity Manager:
```php
$em = $this->getDoctrine()->getEntityManager();
```

Get repository for the data in Entity:
```php
$em->getRepository('MDWDemoBundle:Articles');
```

Data getters:
```php
findAll()
find()
findBy()
findOneBy()
$em->getRepository('MDWDemoBundle:Articles')->findAll();
```

Data setters:
```php
$articulo = new Articles();
$articulo->setTitle('Articulo de ejemplo 1');
$articulo->setAuthor('John Doe');
$articulo->setContent('Contenido');
$articulo->setTags('ejemplo');
$articulo->setCreated(new \DateTime());
$articulo->setUpdated(new \DateTime());
$articulo->setSlug('articulo-de-ejemplo-1');
$articulo->setCategory('ejemplo');

$em = $this->getDoctrine()->getEntityManager();
$em->persist($articulo);
$em->flush();
```

## Testing

## Forms

#### A simple form
```php
// create a task and give it some dummy data for example
$task = new Task();
$task->setTask('New task');
$task->setDueDate(new \DateTime('tomorrow'));

$form = $this->createFormBuilder($task)
    ->add('task', TextType::class)
    ->add('dueDate', DateType::class)
    ->add('save', SubmitType::class, array('label'=> 'Create Task'))
    ->getForm();

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $response = $this->forward('AviaturPruebaBundle:ToDoList:taskSuccess', array(
      'task' => $task,
    ));

    return $response;
}

// if data was to be submitted to database, fetch it to create tasks list

return $this->render($this->get("aviatur_agency_twig_folder")->twigExists('AviaturTwigBundle:' . $agencyFolder . '/Prueba/ToDoList/to_do_list.html.twig'), array(
    'form' => $form->createView(),
));
```

#### Display form
```twig
{# some twig file #}
{{ form_start(form) }}
    {{ form_widget(form) }}
{{ form_end(form) }}
```
OR
```twig
{# some twig file #}
{{ form_start(form, {'attr': {'id': 'form_someId'} }) }}
    {{ form_errors(form) }}

    {{ form_row(form.task) }}
    {{ form_row(form.dueDate) }}

    {{ form_row(form.save) }}
{{ form_end(form) }}
```
OR
```twig
{{ form_start(form, {'attr': {'id': 'form_someId'} }) }}
    {{ form_errors(form) }}
    <div>
        {{ form_label(form.task, 'Task field label') }}
        {{ form_errors(form.task) }}
        {{ form_widget(form.task, {'attr': {'class': 'custom_class'} }) }}
    </div>
    <div>
        {{ form_label(form.dueDate, 'DueDate field label') }}
        {{ form_errors(form.dueDate) }}
        {{ form_widget(form.dueDate, {'attr': {'class': 'some_other_class'} }) }}
    </div>
    <div>
        {{ form_widget(form.save) }}
    </div>
{{ form_end(form) }}
```

#### Handle multiple buttons
```php
if ($form->isValid()) {
    // ... perform some action, such as saving the task to the database
    
    // two possible buttons: 'save' & 'saveAndAdd'
    $nextAction = $form->get('saveAndAdd')->isClicked()
        ? 'task_new'
        : 'task_success';
    
    return $this->redirectToRoute($nextAction);
}
```

## Validation

## Security

## HTTP Cache

## Translation

## Services

## Performance
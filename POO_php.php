<?php

/**
 * https://openclassrooms.com/courses/programmez-en-oriente-objet-en-php 
 */

abstract class Objet // classe abstraite, ne pouvant être instanciée directement, seulement héritée par classes filles
{
  private $_vie = 100;
  
  public function getVie($vie)
  {
    return $this->_vie;
  }
  
  // méthode finale ne pouvant être redéfinie par les classes filles
  final public function setVie($vie) 
  {
    if(!is_int($vie)) 
    {
      trigger_error('La vie doit être un nombre entier', E_USER_WARNING);
      return;
    }
    
    if($vie < 0 || $vie > 100)
    {
      trigger_error('La vie doit être un nombre entier entre 0 et 100', E_USER_WARNING);
      return;
    }
    
    $this->_vie = $vie;
  }
  
  // modèle de méthode devant être redéfinie dans chaque classe fille
  abstract public function hydrate(array $donnees);
}

class Personnage extends Objet implements iMovable, iTalkable
{
  // constantes de classe
  const FORCE_PETITE = 20;
  const FORCE_MOYENNE = 50;
  const FORCE_GRANDE = 80;
  const EXP_MAX = 9999;
  
  // propriété protégée, accessible par les classes filles (notation sans '_')
  protected $attributProtege;
  
  // propriétés privées ne pouvant êtres accédées en dehors du scope de la classe
  private $_force;        		// La force du personnage
  private $_localisation; 		// Sa localisation
  private $_experience = 0;   		// Son expérience
  private $_degats;       		// Ses dégâts
  private $attributs = [];		// array d'attributs customs pouvant être set
  
  // Variable statique PRIVÉE, commune à tous les objets instanciés
  private static $_texteADire = 'Je vais tous vous tuer !';
  
  // méthodes magiques
  public function __construct($force, $degats) // Constructeur demandant 2 paramètres
  {
    echo 'Voici le constructeur !'; 	// Message s'affichant une fois que tout objet est créé.
    $this->setForce($force); 		// Initialisation de la force.
    $this->setDegats($degats); 		// Initialisation des dégâts.
    static::setExperience(1);		// Initialisation de l'expérience à 1, avec résolution statique à la volée (méthode appelée sera celle de la classe invoquée)
  }
  public function __destruct()
  {
    echo 'Destruction de la classe';
  }
  public function __set($nom, $valeur) // intercèpte les attributions invalides (tentatives d'assignations de valeurs à des attributs inexistants ou en dehors du scope car privés / protégés)
  {
    $this->attributs[$nom] = $valeur;
    echo 'Ah, on a tenté d\'assigner à l\'attribut <strong>', $nom, '</strong> la valeur <strong>', $valeur, '</strong> mais c\'est pas possible !<br />';
  }
  public function __get($nom) // intercèpte les tentatives d'accès à attributs invalides
  {
    if (isset($this->attributs[$nom]))
    {
      return $this->attributs[$nom];
    }
    echo 'Impossible d\'accéder à l\'attribut <strong>' . $nom . '</strong>, désolé !<br />';
  }
  public function __isset($nom) // appelée à chaque fois que isset() est appelée sur un attribut invalide
  {
    return isset($this->attributs[$nom]);
  }
  public function __unset($nom) // appelée à chaque fois que unset() est appelée sur un attribut invalide
  {
    if (isset($this->attributs[$nom]))
    {
      unset($this->attributs[$nom]);
    }
  }
  public function __call($nom, $arguments)
  {
    echo 'La méthode <strong>', $nom, '</strong> a été appelée alors qu\'elle n\'existe pas ! Ses arguments étaient les suivants : <strong>', implode ($arguments, '</strong>, <strong>'), '</strong><br />';
  }
  
  public static function __callStatic($nom, $arguments)
  {
    echo 'La méthode <strong>', $nom, '</strong> a été appelée dans un contexte statique alors qu\'elle n\'existe pas ! Ses arguments étaient les suivants : <strong>', implode ($arguments, '</strong>, <strong>'), '</strong><br />';
  }
  // etc. pour plus d'infos sur les méthodes magiques -> https://secure.php.net/manual/en/language.oop5.magic.php
  
  // Un tableau de données doit être passé à la fonction (d'où le préfixe « array »).
  public function hydrate(array $donnees)
  {
    // 'hydrater' revient à fournir à l'objet tous les attributs essentiels à son instanciation pour assurer les fonctionnalités de ses méthodes
    foreach ($donnees as $key => $value)
    {
      // On récupère le nom du setter correspondant à l'attribut.
      $method = 'set'.ucfirst($key);
	  
      // Si le setter correspondant existe.
      if (method_exists($this, $method))
      {
	// On appelle le setter.
	$this->$method($value);
      }
    }
    // Tip: on peut placer un appel à la fonction hydrate() directement dans le constructeur pour obtenir un objet manipulable dés son instanciation
  }
  
  // Nous déclarons une méthode statique (commune à toutes les classes filles) dont le seul but est d'afficher un texte.
  public static function parler()
  {
    // On donne le texte à dire.
    echo self::$_texteADire; // Tip: 'self::' fait référence aux constantes, propriétés statiques et méthodes statiques de la classe même
  }
  
  // getters
  public function getExperience() // Une méthode pour accéder à la propriété $_experience
  {
    return $this->_experience; // '$this' faisant référence à l'objet particulier instancié
  }
  public function getForce()
  {
    return $this->_force;
  }
  public function getDegats()
  {
    return $this->_degats;
  }
  
  // setter chargé de modfier $_experience
  public function setExperience($exp)
  {
    if(!is_int($exp))
    {
      trigger_error('L\'expérience d\'un personnage doit être un nombre entier', E_USER_WARNING);
      return;
    }
    if($exp >= self::EXP_MAX)
    {
      return;
    }
    
    $this->_experience = $exp;
  }
  // setter chargé de modifier l'attribut $_force, toujours vérifier que les valeurs passées correspondent bien aux formats souhaités des attributs
  public function setForce($force)
  {
    // On vérifie qu'on nous donne bien soit un « FORCE_PETITE », soit une « FORCE_MOYENNE », soit une « FORCE_GRANDE ».
    if (in_array($force, [self::FORCE_PETITE, self::FORCE_MOYENNE, self::FORCE_GRANDE]))
    {
      $this->_force = $force;
    }
  }
  // setter chargé de modifier l'attribut $_degats.
  public function setDegats($degats)
  {
    if (!is_int($degats)) // S'il ne s'agit pas d'un nombre entier.
    {
      trigger_error('Le niveau de dégâts d\'un personnage doit être un nombre entier', E_USER_WARNING);
      return;
    }

    $this->_degats = $degats;
  }
  
  public function gagnerExperience() // Une méthode augmentant l'attribut $experience du personnage.
  {
    // On ajoute 1 à notre attribut $_experience.
    $this->_experience++;
  }
  
  public function deplacer() // Une méthode qui déplacera le personnage (modifiera sa localisation).
  {

  }
  
  public function frapper(Personnage $persoAFrapper) // Une méthode qui frappera un personnage (suivant la force qu'il a).
  {
    $persoAFrapper->_degats += $this->_force;
  }
  
  public function lancerFruits(array $bolDeFruits) // Une méthode pour lancer des fruits...
  {
    $nbrFruits = count($bolDeFruits);
    for($i = 0; i < $nbrFruits; $i++) {
      echo "lance " . $bolDeFruits[$i] . "!";
    }
  }
}



// charger classes définies dans des fichiers externes
require 'MaClasse.php'; // inclus la classe (selon les fichiers dans la liste des répertoires de l'include_path')

// auto-load
function chargerClasse($classe)
{
  require $classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}
spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.



// Namespaces
namespace Tutoriel\HTML;
class Form {
    // ...
}
use \Tutoriel\Html\Form ;
new Form(); 



// Instanciation / appel des méthodes
$perso = new Personnage(Personnage::FORCE_PETITE, 0);
Personnage::parler(); // utliser le machin Nekudotayim pour invoquer une méthode statique <=> commune à toutes les instances de la classe (donc pas besoin de faire référence à un objet directement)
$perso->_experience = $perso->_experience + 1; // Une erreur fatale est levée suite à cette instruction.
$perso->getExperience(); // 1
$perso->getForce(); // 0
$perso->getDegats(); // 0

// On crée deux personnages
$perso1 = new Personnage(Personnage::FORCE_MOYENNE, 0);
$perso2 = new Personnage(Personnage::FORCE_PETITE, 20);
    
$perso1->frapper($perso2); // $perso1 frappe $perso2
$perso1->gagnerExperience(); // $perso1 gagne de l'expérience

echo 'Le personnage 1 a ', $perso1->force(), ' de force, contrairement au personnage 2 qui a ', $perso2->force(), ' de force.<br />';



/**
 * Manager type CRUD pour gérer la persistance en BDD
 */
class PersonnagesManager
{
  private $_db; // Instance de PDO

  public function __construct($db)
  {
    $this->setDb($db);
  }

  public function add(Personnage $perso)
  {
    $q = $this->_db->prepare('INSERT INTO personnages(nom, forcePerso, degats, niveau, experience) VALUES(:nom, :forcePerso, :degats, :niveau, :experience)');

    $q->bindValue(':nom', $perso->nom());
    $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);
    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
    $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);

    $q->execute();
  }

  public function delete(Personnage $perso)
  {
    $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->id());
  }

  public function get($id)
  {
    $id = (int) $id;

    $q = $this->_db->query('SELECT id, nom, forcePerso, degats, niveau, experience FROM personnages WHERE id = '.$id);
    $donnees = $q->fetch(PDO::FETCH_ASSOC);

    return new Personnage($donnees);
  }

  public function getList()
  {
    $persos = [];

    $q = $this->_db->query('SELECT id, nom, forcePerso, degats, niveau, experience FROM personnages ORDER BY nom');

    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
      $persos[] = new Personnage($donnees);
    }

    return $persos;
  }

  public function update(Personnage $perso)
  {
    $q = $this->_db->prepare('UPDATE personnages SET forcePerso = :forcePerso, degats = :degats, niveau = :niveau, experience = :experience WHERE id = :id');

    $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);
    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
    $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);
    $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);

    $q->execute();
  }

  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
}

// création d'un nouveau personnage
$perso = new Personnage([
  'nom' => 'Victor',
  'forcePerso' => 5,
  'degats' => 0,
  'niveau' => 1,
  'experience' => 0
]);

$db = new PDO('mysql:host=localhost;dbname=tests', 'root', '');
$manager = new PersonnagesManager($db);
    
$manager->add($perso);



/**
 * Héritage
 */
final class Magicien extends Personnage // classe finale ne pouvant elle-même être héritée
{
  private $_magie; // Indique la puissance du magicien sur 100, sa capacité à produire de la magie.
  
  public function lancerUnSort($perso)
  {
    $perso->recevoirDegats($this->_magie); // On va dire que la magie du magicien représente sa force.
  }
  
  public function gagnerExperience()
  {
    // On appelle la méthode gagnerExperience() de la classe parente
    parent::gagnerExperience();
    
    if ($this->_magie < 100)
    {
      $this->_magie += 10;
    }
  }
}

$copie = clone $origine; // On copie le contenu de l'objet $origine dans l'objet $copie.

$a = new A;
$b = new B; // $b != $a, deux objets différents
$c = new A; // $c == $a, nouvelle instance de même classe et attributs identiques
$d = $a;    // $d === $a, $d est un pointeur vers le même objet que $a en quelque sorte



// parcours d'un objet, comme les tableaux, affichera les attributs accessibles depuis le scope de la boucle
foreach($class as $key => $value) {
  echo '<strong>', $key, '</strong> => ', $value, '<br />';
}



// Interfaces: pour définir les constantes communes et méthodes devant être définies dans les classes implétant ces interfaces
interface iMovable 
{
  const SPEED_MIN = 0;
  const SPEED_MAX = 1000;
  
  public function deplacer(); // prototype de fonction à définir
}
interface iTalkable 
{
  public function parler();
}
// Tip: les interfaces peuvent être héritées entre elles comme les classes avec 'extends', peuvent aussi hériter de plusieurs interfaces à la fois, mais ne peuvent pas override les protos de méthodes héritées

// interfaces prédéfinies: Iterator, SeekableIterator, ArrayAccess, Countable, ArrayIterator...
// -> https://secure.php.net/manual/en/reserved.interfaces.php



// Exceptions
class MonException extends Exception
{
  public function __construct($message, $code = 0)
  {
    parent::__construct($message, $code);
  }
  
  public function __toString()
  {
    return 'Error ' . $this->code . ': ' . $this->message . ' on <strong>' . $this->line . '</strong> in ' . $this->file;
  }
  // cf. http://fr2.php.net/manual/en/class.exception.php
}

function additionner($a, $b)
{
  if (!is_numeric($a) || !is_numeric($b))
  {
    throw new MonException('Les deux paramètres doivent être des nombres', 1);
  } elseif($a == 0 || $b == 0) {
    throw new Exception('Can\'t you guess that by yourself ?');
    // Tip: on peut aussi lancer des Exceptions non customss mais avec des noms prédéfinis pour plus de clarté, cf. http://fr2.php.net/manual/en/spl.exceptions.php
  }
  
  return $a + $b;
}

try // Nous allons essayer d'effectuer les instructions situées dans ce bloc.
{
  echo additionner(12, 3), '<br />';
  echo additionner('azerty', 54), '<br />';
  echo additionner(4, 8);
}
catch (MonException $e) // Nous allons attraper les exceptions "MonException" s'il y en a une qui est levée.
{
  echo '[MonException] : ', $e; // On affiche le message d'erreur grâce à la méthode __toString que l'on a écrite.
}
catch (Exception $e) // Si l'exception n'est toujours pas attrapée, alors nous allons essayer d'attraper l'exception "Exception".
{
  echo '[Exception] : ', $e->getMessage(); // La méthode __toString() nous affiche trop d'informations, nous voulons juste le message d'erreur.
}
finally
{
  echo 'Action effectuée quoi qu\'il arrive';
}

echo 'Fin du script'; // Ce message s'affiche, ça prouve bien que le script est exécuté jusqu'au bout.



// Convertir les erreurs en exceptions
class MonException extends ErrorException
{
  public function __toString()
  {
    switch ($this->severity)
    {
      case E_USER_ERROR : // Si l'utilisateur émet une erreur fatale.
        $type = 'Erreur fatale';
        break;
      
      case E_WARNING : // Si PHP émet une alerte.
      case E_USER_WARNING : // Si l'utilisateur émet une alerte.
        $type = 'Attention';
        break;
      
      case E_NOTICE : // Si PHP émet une notice.
      case E_USER_NOTICE : // Si l'utilisateur émet une notice.
        $type = 'Note';
        break;
      
      default : // Erreur inconnue.
        $type = 'Erreur inconnue';
        break;
    }
    
    return '<strong>' . $type . '</strong> : [' . $this->code . '] ' . $this->message . '<br /><strong>' . $this->file . '</strong> à la ligne <strong>' . $this->line . '</strong>';
  }
}

function error2exception($code, $message, $fichier, $ligne)
{
  // Le code fait office de sévérité.
  // Reportez-vous aux constantes prédéfinies pour en savoir plus.
  // http://fr2.php.net/manual/fr/errorfunc.constants.php
  throw new MonException($message, 0, $code, $fichier, $ligne);
}

function customException($e)
{
  echo 'Ligne ', $e->getLine(), ' dans ', $e->getFile(), '<br /><strong>Exception lancée</strong> : ', $e->getMessage();
}

set_error_handler('error2exception');
set_exception_handler('customException'); // intercepte les exceptions non attrapées



// Traits: méthodes et caractéristiques réutilisables idépendament dans différentes classes
trait HTMLFormater
{
  public function formatHTML($text)
  {
    return '<p>Date : '.date('d/m/Y').'</p>'."\n".
           '<p>'.nl2br($text).'</p>';
  }
}

trait TextFormater
{
  public function formatText($text)
  {
    return 'Date : '.date('d/m/Y')."\n".$text;
  }
}

class Writer
{
  use HTMLFormater, TextFormater;
  
  public function write($text)
  {
    file_put_contents('fichier.txt', $this->formatHTML($text));
  }
}



// obtenir des informations sur une classe / interface: l'API de reflexivité
$classeMagicien = new ReflectionClass('Magicien');
// -> https://secure.php.net/manual/en/class.reflectionclass.php



// pour la représentation UML et transcription en backbone:
// -> http://dia-installer.de/
// -> http://uml2php5.zpmag.com/



/**
 * Design patterns
 */
 
// Factory: laisser des classes usine créer les instances
class PDOFactory
{
  public static function getMysqlConnexion()
  {
    $db = new PDO('mysql:host=localhost;dbname=tests', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $db;
  }
  
  public static function getPgsqlConnexion()
  {
    $db = new PDO('pgsql:host=localhost;dbname=tests', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $db;
  }
}

// Observers: lier certains objets à des « écouteurs » eux-mêmes chargés de notifier les objets auxquels ils sont rattachés
$mailSender = new class('login@fai.tld') implements SplObserver // classe anonyme
{
  protected $mail;
  
  public function __construct($mail)
  {
    if (preg_match('`^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$`', $mail))
    {
      $this->mail = $mail;
    }
  }
  
  public function update(SplSubject $obj)
  {
    mail($this->mail, 'Erreur détectée !', 'Une erreur a été détectée sur le site. Voici les informations de celle-ci : ' . "\n" . $obj->getFormatedError());
  }
};

$db = PDOFactory::getMysqlConnexion();
$dbWriter = new class($db) implements SplObserver
{
  protected $db;
  
  public function __construct(PDO $db)
  {
    $this->db = $db;
  }
  
  public function update(SplSubject $obj)
  {
    $q = $this->db->prepare('INSERT INTO erreurs SET erreur = :erreur');
    $q->bindValue(':erreur', $obj->getFormatedError());
    $q->execute();
  }
};

$o = new ErrorHandler; // Nous créons un nouveau gestionnaire d'erreur.

$o->attach($mailSender)
  ->attach($dbWriter);

set_error_handler([$o, 'error']); // Ce sera par la méthode error() de la classe ErrorHandler que les erreurs doivent être traitées.

5 / 0; // Générons une erreur
// à propos de spl -> https://secure.php.net/manual/en/book.spl.php

$textFormater = new class implements Formater
{
  public function format($text)
  {
    return 'Date : ' . time() . "\n" . 'Texte : ' . $text;
  }
};

// Strategy: délocaliser la partie algorithmique d'une méthode afin de le permettre réutilisable, évitant ainsi la duplication de cet algorithme
$htmlFormater = new class implements Formater
{
  public function format($text)
  {
    return '<p>Date : ' . time() . '<br />' ."\n". 'Texte : ' . $text . '</p>';
  }
};

$xmlFormater = new class implements Formater
{
  public function format($text)
  {
    return '<?xml version="1.0" encoding="ISO-8859-1"?>' ."\n".
           '<message>' ."\n".
           "\t". '<date>' . time() . '</date>' ."\n".
           "\t". '<texte>' . $text . '</texte>' ."\n".
           '</message>';
  }
};

function autoload($class)
{
  if (file_exists($path = $class . '.php'))
  {
    require $path;
  }
}

spl_autoload_register('autoload');

$writer = new FileWriter($htmlFormater, 'file.html');
$writer->write('Hello world !');

// Singleton: instancier une classe une seule et unique fois
class MonSingleton
{
  protected static $instance; // Contiendra l'instance de notre classe.
  
  protected function __construct() { } // Constructeur en privé.
  protected function __clone() { } // Méthode de clonage en privé aussi.
  
  public static function getInstance()
  {
    if (!isset(self::$instance)) // Si on n'a pas encore instancié notre classe.
    {
      self::$instance = new self; // On s'instancie nous-mêmes. :)
    }
    
    return self::$instance;
  }
}



// Générateur: itérer dans une structure, délivrant ('yield') une valeur à chaque itération jusqu'à la fin de l'itération
function generator()
{
  for ($i = 0; $i < 10; $i++)
  {
    yield 'Itération n°'.$i;
  }
}

foreach (generator() as $key => $val)
{
  echo $key, ' => ', $val, '<br />';
}
// cf. https://secure.php.net/manual/en/language.generators.syntax.php

// On peut utiliser les références aux valeurs d'un tableau pour modifier les valeurs d'un tableau d'une classe grâce à un générateur par exemple
class SomeClass
{
  protected $attr;

  public function __construct()
  {
    $this->attr = ['Un', 'Deux', 'Trois', 'Quatre'];
  }

  // Le & avant le nom du générateur indique que les valeurs retournées sont des références
  public function &generator()
  {
    // On cherche ici à obtenir les références des valeurs du tableau pour les retourner
    foreach ($this->attr as &$val)
    {
      yield $val;
    }
  }

  public function attr()
  {
    return $this->attr;
  }
}

$obj = new SomeClass;

// On parcourt notre générateur en récupérant les entrées par référence
foreach ($obj->generator() as &$val)
{
  // On effectue une opération quelconque sur notre valeur
  $val = strrev($val);
}

echo '<pre>';
var_dump($obj->attr());
echo '</pre>';



// Générateurs inverses (coroutines)
function generator()
{
  echo yield;
}

$gen = generator();
$gen->send('Hello world !');



// Closures
function creerAdditionneur($quantite)
{
  return function($nbr) use($quantite) // closure (une fonction anonyme en gros) ici utilisée comme callback
  {
    return $nbr + $quantite;
  };
}

$listeNbr = [1, 2, 3, 4, 5];

$listeNbr = array_map(creerAdditionneur(5), $listeNbr);
var_dump($listeNbr);
// On a : $listeNbr = [6, 7, 8, 9, 10]

$listeNbr = array_map(creerAdditionneur(4), $listeNbr);
var_dump($listeNbr);
// Cette fois-ci, on a bien : $listeNbr = [10, 11, 12, 13, 14]

// Lier une closure à un objet
$additionneur = function()
{
  $this->_nbr += 5;
};

class MaClasse
{
  private $_nbr = 0;

  public function nbr()
  {
    return $this->_nbr;
  }
  
  // Liaison automatique
  public function getAdditionneur()
  {
    return function() // closure
    {
      $this->_nbr += 5; // adopte le contexte de l'objet de la méthode qui l'appelle (de même pour un contexte statique)
    };
  }
}

$obj = new MaClasse;

// On obtient une copie de notre closure qui sera liée à notre objet $obj
// Cette nouvelle closure sera appelée en tant que méthode de MaClasse
// On aurait tout aussi bien pu passer $obj en second argument
$additionneur = $additionneur->bindTo($obj, 'MaClasse');
// ou
$additionneur = $obj->getAdditionneur();

$additionneur();

echo $obj->nbr(); // Affiche bien 5



// Lier une closure à une classe
$additionneur = function()
{
  self::$_nbr += 5;
};

class MaClasse
{
  private static $_nbr = 0;

  public static function nbr()
  {
    return self::$_nbr;
  }
}

$additionneur = $additionneur->bindTo(null, 'MaClasse');
$additionneur();

echo MaClasse::nbr(); // Affiche bien 5



// Lier temporairement une closure à un objet
class Nombre
{
  private $_nbr;
  
  public function __construct($nbr)
  {
    $this->_nbr = $nbr;
  }
}

$closure = function() {
  var_dump($this->_nbr + 5);
};

$two = new Nombre(2);
$three = new Nombre(3);

$closure->call($two);
$closure->call($three);
<!DOCTYPE html>
<html lang="pl">
<head>

<title>Aplikacja Pracownicza</title>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="style.css">

<!-- JavaScript for Bootstrap -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

<script >
$(document).ready(function() {
  $("#btn").click(function(){
    $.ajax({
      type: "GET",
      url: "ajax.php",
      success: function(data) {
        var obj = jQuery.parseJSON(data);

        var text = '<div style="margin: 10px 0px;">' + $('#response').html() + obj.Caption + "</div><br>";
        $('#response').html(text);
      }
    });
  });
});
</script>
</head>


<script type="text/javascript">
function myValidation() {
  // Get the value of NAME form field
  let x = document.forms["postForm"]["NAME"];
  // If x is empty alert the user
  if (x.value ==""){
    alert("Nazwa firmy musi być wypełniona, aby edytować wpis!");
    document.x.style.backgroundColor = red;
  } 
}
</script>

<body>

<?php 

var_dump($_POST); echo"<BR>";


// create abstract class for company
class Company {
  public $ID;
  public $NAME;
  public $LOCATION;

  function _toString() {
    return "Company #".$ID." named: ".$NAME." in ".$LOCATION;
  }
};

//  load config
$GLOBALS['config'] = include ('config.php');

// connect to database
function conn_PDO() {
    try {
      // create Data Source Name
      $dsn = "mysql:host=".$GLOBALS['config']['host'].";dbname=".$GLOBALS['config']['db'].";charset=".$GLOBALS['config']['charset'];

      //  create PDO instance
      return new PDO($dsn,$GLOBALS['config']['username'],$GLOBALS['config']['password']);
      echo '<div class="response">Success!</div>';
    } catch (PDOException $e){
      die($e->getMessage() . " - code_error: " . $e->getCode());
    }
}
// connection instance with database
$conn = conn_PDO();


// get company ID
function getCompanyID() {
  $id = isset($_GET['ID']) && is_numeric($_GET['ID']) ? (int)$_GET['ID'] : $_GET['ID']=1;
  return $id;
}

// get Company object to work on
function getCompanyObject($ID, $conn) {
  
  $company = $conn->query("SELECT * FROM company WHERE ID = $ID")->fetchAll(PDO::FETCH_CLASS, 'Company');

  return $company ? $company : null;// != null ? $company : "echo TRASH";
}

// To UPDATE row from POST
function editRow($conn){
  if(!empty($_POST['NAME'])){

    $sql = "UPDATE company SET NAME=?, LOCATION=? WHERE id=?";
    $stmt= $conn->prepare($sql);
    $stmt->execute([$_POST['NAME'], $_POST['LOCATION'], $_POST['I']]);  
  
  }
}
// To INSERT new row
function insertRow($conn, $name, $location){
  if(!empty($_POST['NAMES'])){

    $sql = "INSERT INTO company (NAME, LOCATION) VALUES(?, ?)";
    $stmt= $conn->prepare($sql);
    $stmt->execute([$name, $location]);  

  }
  $_POST=array();

  echo '
    <script type="text/javascript">
    myHeaders.delete(NAMES);
    </script>';

}

// Set a flag to control forms
$GLOBALS['formFlag'] = 1;


// Check which form to load
if(isset($_POST['post2'])){
  $GLOBALS['formFlag'] = $_POST['choice'];

}

if(isset($_POST['post1'])){
  editRow($conn);
}

if(isset($_POST['post0'])){
  insertRow($conn, $_POST['NAMES'], $_POST['LOCATION']);
}

// GET ID
$ID = getCompanyID();
// GET Company Object
$company = getCompanyObject($ID, $conn);


// Load the form body

function loadForm($c) {
  // Check if company object is viable
  // If not load error

  if(!$c){
    $GLOBALS['formFlag']=10;
  } else {
    $ID= $c[0]->{'ID'};
    $N = $c[0]->{'NAME'};
    $L = $c[0]->{'LOCATION'};
  }

    echo <<< CHC
    <form action="index.php" name="choiceForm" method="POST">
    <div style="display:flex; flex-direction:row; justify-content: space-evenly;">
  

      <div>
        <input type="radio"  name="choice" value="0" >
        <label for="insert">Dodaj Nowy</label>
      </div>
    
      <div>
        <input type="radio"  name="choice" value="1" checked>
        <label for="edit">Edytuj</label>
      </div>

      <div>
        <input type="submit" name="post2">
      </div>

      </form>
    </div>

    CHC;
    
    switch ((int)$GLOBALS['formFlag']){
      
      //Case for New Row form
      case 0:
      echo <<< FRM
      <form name="postForm"  method="POST" action="index.php">
  
      <div class="row" style="display:flex; justify-content: center; margin-bottom: 2rem;">
        <h2> Nowy Wpis </h2>
      </div>
      
      <div class="row" style="margin-bottom: 1rem;">
        <div class="col">
          <h3>Nazwa Firmy</h3>
        </div>
        <div class="col">
          <input type="text" id="inname" name="NAMES" value="">
        </div>
      </div>
      
      <div class="row" style="margin-bottom: 1rem;">
        <div class="col">
          <h3>Lokalizacja Firmy</h3>
        </div>
        <div class="col">
          <input type="text" name="LOCATION" value="">
        </div>
      </div>
      
      <div class="subdiv row" style="display: flex; justify-content: flex-end; padding: 0 2rem;">
        <input type="submit" name="post0" onclick="myValidation()" value="Dodaj">
      </div>
        </form>
      FRM;
      break;
      
      // Case for Editing Form
      case 1:

      echo <<< FRM

      <form name="postForm" method="POST" action="index.php?ID=$ID">
  
      <div class="row" style="display:flex; justify-content: center; margin-bottom: 2rem;">
        <h2> Edytowany Wpis </h2>
      </div>
      
      <div class="row" style="margin-bottom: 1rem;">
        <div class="col">
          <h3>Identyfikator Firmy</h3>
        </div>
        <div class="col">
          <input style="background: #e6e6fa" type="text" name="I" readonly value=$ID>
        </div>
      </div>
      
      <div class="row" style="margin-bottom: 1rem;">
        <div class="col">
          <h3>Nazwa Firmy</h3>
        </div>
        <div class="col">
          <input type="text" id="inname" name="NAME" value=$N>
        </div>
      </div>
      
      <div class="row" style="margin-bottom: 1rem;">
        <div class="col">
          <h3>Lokalizacja Firmy</h3>
        </div>
        <div class="col">
          <input type="text" name="LOCATION" value=$L>
        </div>
      </div>
      
      <div class="subdiv row" style="display: flex; justify-content: flex-end; padding: 0 2rem;">
        <input type="submit" name="post1" onclick="myValidation()" value="Edytuj">
      </div>
        </form>
  
  
      FRM;

      break;
      // ERROR CASE

      default:
      echo <<< ERR
        <h2 style="margin: 10rem 3rem;"> Nie udało się załadować formularza! </h2>
      ERR;
      break;

  }
}

?>

<div style="display: flex; flex-direction: column; justify-content: space-evenly;">

<div class="form-wrapper" style="padding: 15px; margin: 20px; border: solid black 1px; background: purple; display: flex; justify-content: space-evenly; min-height: 27rem;">

  <div style="width: 80%; border: solid black 1px; padding: 10px; background: white;">

    <?php loadForm($company); ?>

  </div>

</div>

<div class="container">

    <div class="row">
      <button type="button" id="btn">Pobierz JSONa</button>
    </div>
    <div class="row" id="response" >
    </div>
  </div>

</div>

</body>
</html>
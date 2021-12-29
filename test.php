<form action="test.php" id="choice" method="POST">
  <input type="radio"  name="choice" value="Nowy" checked>
  <label for="insert">Dodaj Nowy</label>
  <input type="radio"  name="choice" value="Edytuj" >
  <label for="edit">Edytuj</label>
  <input type="submit">
</form>

<br><br>
<?php echo "<h1>";var_dump ($_POST); echo "</h1>";?>



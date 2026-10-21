<div>
<form name='mensaje' method="POST">
Tema<br>
 <input type="text" name="tema" size=30 
   value="<?=(isset($_REQUEST['tema']))?$_REQUEST['tema']:''?>" ><br>
Comentario: <br>
<textarea name="comentario" rows="4" cols="50" maxlength="300">
<?= $_REQUEST['comentario']??''?>
</textarea>
<br><br>
<input type="submit" name="orden" value="Detalles">
<input type="submit" name="orden" value="Nueva opinión">
<input type="submit" name="orden" value="Terminar">
</form>
</div>

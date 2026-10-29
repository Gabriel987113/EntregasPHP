<html>
<head>
<meta charset="UTF-8">
<title>Minicasino</title>
</head>
<body>
 <h1> BIENVENIDO AL CASINO</h1> <br>
  Esta es su <?= $_COOKIE["visitas"] ?>º visita.<br>
 <form method="post">
 Introduzca el dinero con el que va jugar:
 <input name="cantidadini" type="number">		
 <button type="submit">Empezar a jugar</button>
</form>
</body>
<!--1.Primera entrada: muestra la bienvenida y permite introducir el dinero inicial.

2.Primera apuesta: muestra el saldo disponible y permite apostar una cantidad y elegir PAR o IMPAR.

3.Si gana: suma al saldo el importe apostado.

4.Si intenta apostar más dinero del que tiene: muestra un error y mantiene el saldo.

5.Si abandona o se queda sin dinero: muestra la despedida y el saldo final.

6.Siguiente entrada: muestra que es la segunda visita, gracias a la cookie. -->
</html>
        

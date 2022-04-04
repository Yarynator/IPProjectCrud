# IPProjectCrud
Živý prohlížeč databáze můžete najít na adrese [databáze](http://b2019biskma.delta-www.cz/prohlizecv2).

## Příhlášení
Pro přihlášení můžete použít uživatelské jméno ```fantomas``` s heslem ```abcd```.

## Zprovoznění kódu na svém stroji
Pro zprovoznění na svém serveru je potřeba projít 2 kroky.
1. Upravit soubor ```LocalConfig.class.example.php``` na data svojí databáze (databáze by měla obsahovat tabulky employee, room a key) a následně je potřeba ho uložit jako ```LocalConfig.class.php```.
2. Zprovoznit composer a provést příkaz ```composer update``` v root adresáři projektu.

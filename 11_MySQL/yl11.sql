/*Uue tabeli loomine:*/
CREATE TABLE loomaaedMPilvik(
	id integer PRIMARY KEY AUTO_INCREMENT ,
	nimi varchar( 100 ) ,
	vanus integer,
	liik varchar( 200 ) ,
	puur integer
)

/*Eelnevalt loodud tabeli täitmine vähemalt 5 reaga*/
INSERT INTO loomaaedMPilvik (nimi, vanus, liik, puur) VALUES
("Bobo", 12, "gorilla", 1),
("Chiquita", 28, "gorilla", 2),
("Laine", 2, "jänes", 3),
("Leo", 8, "leopard", 4),
("Toomas", 5, "tiiger", 5),
("Madli", 4, "tiiger", 5),
("Makale", 9, "lõvi", 6),
("Elsa", 6, "lõvi", 6)

/*Kõigi mingis ühes kindlas puuris elavate loomade nimi ja puuri number*/
SELECT nimi, puur 
FROM loomaaedMPilvik 
WHERE puur=5;

/*Vanima ja noorima looma vanused*/
SELECT max(vanus) as vanim, min(vanus) as noorim
FROM loomaaedMPilvik;

/*Puuri number koos selles elavate loomade arvuga*/
SELECT puur, count(*) as loomi
FROM loomaaedMPilvik
GROUP BY puur;

/*Kõikide tabelis olevate vanuste 1 aasta võrra suurendamine*/
UPDATE loomaaedMPilvik
SET vanus=vanus+1;
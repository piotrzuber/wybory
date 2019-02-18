CREATE TABLE wyborca (
id number(15) PRIMARY KEY,
nr_indeksu VARCHAR2(9) NOT NULL UNIQUE,
imie VARCHAR2(20) NOT NULL,
nazwisko VARCHAR2(20) NOT NULL,
rok_studiow NUMBER(1) NOT NULL,
czlonek_komisji CHAR(1) NOT NULL,
dodany_przez number(15) NOT NULL REFERENCES WYBORCA,
CONSTRAINT CHK_wyborca_czlonek_komisji CHECK (czlonek_komisji in ('T', 'N'))
)

CREATE TABLE wybory (
id number(15) PRIMARY KEY,
nazwa VARCHAR2(50) NOT NULL UNIQUE,
poczatek_zglaszania DATE NOT NULL,
koniec_zglaszania DATE NOT NULL,
poczatek DATE NOT NULL,
koniec DATE NOT NULL
)

CREATE TABLE kandydat (
id number(15) primary key,
id_wyborca number(15) NOT NULL REFERENCES wyborca,
id_wybory number(15)  NOT NULL REFERENCES wybory,
glosy_otrzymane NUMBER(5) DEFAULT 0,
id_dodany_przez  number(15) NOT NULL REFERENCES wyborca
)

CREATE TABLE glos (
id number(15) primary key,
id_wyborca number(15) NOT NULL REFERENCES wyborca,
id_wybory number(15)  NOT NULL REFERENCES wybory
)

ALTER TABLE glos
ADD CONSTRAINT UC_glos UNIQUE (id_wyborca, id_wybory); 

ALTER TABLE kandydat
ADD CONSTRAINT UC_kandydayt UNIQUE (id_wyborca, id_wybory);

--ci¹gi nowych id
CREATE SEQUENCE id_wyborca_seq;
CREATE SEQUENCE id_wybory_seq;
CREATE SEQUENCE id_kandydat_seq;
CREATE SEQUENCE id_glos_seq;

--trigger na nowego kandydata

CREATE OR REPLACE TRIGGER kandydat_biu
  BEFORE INSERT OR UPDATE OF id_wyborca ON kandydat
  FOR EACH ROW
DECLARE
  v_czlonek_komisji CHAR;
BEGIN
  SELECT czlonek_komisji
    INTO v_czlonek_komisji
    FROM wyborca
    WHERE id = :new.id_wyborca;
  IF v_czlonek_komisji = 'T' THEN
    RAISE_APPLICATION_ERROR(-20000, 'Cz³onek komisji nie mo¿e byæ kandydatem w wyborach');
  END IF;
END kandydat_biu;
/

--trigger na pilnowanie w³aœciwych dat wyborów

CREATE OR REPLACE TRIGGER wybory_biu
  BEFORE INSERT OR UPDATE ON wybory
  FOR EACH ROW
DECLARE
  v_data DATE;
BEGIN
  SELECT SYSDATE
    INTO v_data
    FROM dual;
  IF :new.koniec_zglaszania < v_data THEN
    RAISE_APPLICATION_ERROR(-20000, 'Potrzeba min dnia na zg³oszenie kandydatur');
  END IF;
  IF :new.koniec < :new.koniec_zglaszania + 3 THEN
    RAISE_APPLICATION_ERROR(-20000, 'Wybory powinny trwaæ min 3 dni od koñca zg³aszania kandydatur');
  END IF;
  IF :new.poczatek < :new.koniec_zglaszania THEN
    RAISE_APPLICATION_ERROR(-20000, 'Wybory nie mog¹ rozpocz¹æ siê wczeœniej ni¿ dzieñ po koñcu og³aszania kandydatur');
  END IF;
  IF :new.koniec <= :new.poczatek OR :new.koniec_zglaszania <= :new.poczatek_zglaszania THEN
    RAISE_APPLICATION_ERROR(-20000, 'Koniec wydarzenia nie mo¿e nastêpowaæ przed jego pocz¹tkiem');
  END IF;
END wybory_biu;
/

-- procedura przeprowadzj¹ca proces oddania g³osu

CREATE OR REPLACE PROCEDURE oddaj_glos(id_wyborcy IN NUMBER, id_kandydata IN NUMBER)
AS
  v_wybory NUMBER;
  oddane_glosy NUMBER;
BEGIN
  SELECT id_wybory
    INTO v_wybory
    FROM kandydat
    WHERE id = id_kandydata;
  SELECT COUNT(*)
    INTO oddane_glosy
	FROM glos
	WHERE id_wyborca = id_wyborcy AND id_wybory = v_wybory;
  IF oddane_glosy > 0 THEN
    RAISE_APPLICATION_ERROR(-20000, 'Wyborca odda³ ju¿ g³os w tych wyborach');
  ELSE
    INSERT INTO glos(id, id_wyborca, id_wybory)
      VALUES(id_glos_seq.NEXTVAL, id_wyborcy, v_wybory);
    UPDATE kandydat
      SET glosy_otrzymane = glosy_otrzymane + 1
      WHERE id = id_kandydata AND id_wybory = v_wybory;
  END IF;
END;
/

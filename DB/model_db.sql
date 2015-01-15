-- Database: "Cataleg_Pelis"

-- DROP DATABASE "Cataleg_Pelis";

CREATE DATABASE "Cataleg_Pelis"
  WITH OWNER = barba
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'ca_ES.UTF-8'
       LC_CTYPE = 'ca_ES.UTF-8'
       CONNECTION LIMIT = -1;

COMMENT ON DATABASE "Cataleg_Pelis"  IS 'Catàleg de pelicules descarregades';


-- Drop table Pelis_Down

CREATE TABLE Pelis_Down (
   id_peli				numeric			not null, 
   id_versio			numeric			not null, 
   titol				text			not null,
   titol_original		text			null,
   idioma_audio			char(3)			null,
   idioma_subtitols		char(3)			null,
   url_imdb				text			null,
   url_filmaffinity		text			null,
   qualitat_video		text			null,
   qualitat_audio		text			null,
   any_estrena					int				null,
   director				text			null,
   CONSTRAINT Pelis_Down_PK PRIMARY KEY (id_peli, id_versio)
) 
WITH (
  OIDS = FALSE
);
ALTER TABLE Pelis_Down   OWNER TO barba;
COMMENT ON TABLE Pelis_Down  IS 'Pelicules descarregades';



-- Drop table Arxius_Pelis

CREATE TABLE Arxius_Pelis (
   id_peli				numeric			not null, 
   id_versio			numeric			not null, 
   num_arxiu			numeric			not null,
   url					text			null,
   tamany				numeric			null,
   tipus_arxiu			varchar(50)		null,
   nom_arxiu			text			null,
   nom_arxiu_original	text			null,
   CONSTRAINT Arxius_Pelis_PK PRIMARY KEY (id_peli, id_versio, num_arxiu)
) WITH (OIDS = FALSE);
ALTER TABLE Arxius_Pelis   OWNER TO barba;
COMMENT ON TABLE Arxius_Pelis  IS 'Arxius de pelicules';



="insert into Arxius_Pelis values ("  
& B3 & ", "  
& SI(C3="";"1";C3) & ", "  
& SI(D3="";"1";D3) & ", '"  
& E3 & "', "  
& F3 & ", '"  
& G3 & "', '"  
& SUBSTITUEIX(H3; "'"; "''") & "', '"  
& SUBSTITUEIX(I3; "'"; "''") & "');"






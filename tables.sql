DROP TABLE IF EXISTS frogs;
CREATE TABLE frogs
(
  id                smallint unsigned NOT NULL auto_increment,
--   publicationDate date NOT NULL,                              
  species           varchar(255) NOT NULL,                      
  color             varchar(255) NOT NULL,                           
  weight_kg         varchar(50) NOT NULL,
  is_poisonous      boolean NOT NULL DEFAULT FALSE,
  created_at        date NOT NULL,
  updated_at        date NULL,          

  PRIMARY KEY (id)
);
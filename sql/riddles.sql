CREATE TABLE riddles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    riddle VARCHAR(500) NOT NULL,
    answer VARCHAR(100) NOT NULL,
    hint VARCHAR(255),
    roomId INT NOT NULL
);

-- Raadsels voor het verlaten pretpark
INSERT INTO riddles (riddle, answer, hint, roomId) VALUES
('Ik draaide vroeger rond en rond, met muziek en vrolijkheid. Nu ben ik stil en roestig, met alleen vogels als gezelschap. Kinderen zaten ooit op mijn paardenrug. Wat ben ik?', 'Carrousel', 'Het draait in een kring, maar nu niet meer', 1),

('Ik schoot je hoog in de lucht, je hart in je keel. Nu lig ik gebroken en roestig, mijn rails naar het niets. Duizenden mensen schreeuwden van plezier op mij. Wat ben ik?', 'Achtbaan', 'Een metallisch monster met bochten en hoogte', 1),

('Ik zag voetafdrukken op mijn vloer, lachende gezichten in mijn spiegels. Nu ben ik verlaten en donker, fluisteringen echo''en tegen mijn wanden. Kinderen durfden niet verder dan halverwege. Wat ben ik?', 'Spiegeldoolhof', 'Je raakt jezelf aangezicht in aangezicht, maar het is nep', 1);


 INSERT INTO riddles (riddle, answer, hint, roomId) VALUES
('Ik draaide hoog boven het park en liet je alles van boven zien. Nu piep ik zacht in de wind. Wat ben ik?', 
'Reuzenrad', 
'Een groot rad met gondels', 
3),

('Mensen gingen in mij zitten en botsten lachend tegen elkaar aan. Nu sta ik stil met kapotte auto’s op de vloer. Wat ben ik?', 
'Botsauto’s', 
'Auto’s die expres tegen elkaar botsen', 
3),

('Vroeger hoorde je muziek en zag je dansende lichtjes. Mensen probeerden prijzen te winnen bij mijn spelletjes. Nu ben ik donker en leeg. Wat ben ik?', 
'Kermiskraam', 
'Hier kon je prijzen winnen', 
3);
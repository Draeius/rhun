
-- Fragt den Namen und Titel des neuesten Charakters ab

SELECT 
    r.name, r.gender, r.coloredName, r.title, r.isInFront
FROM
    (SELECT 
        c.name,
            c.gender,
            n.name AS coloredName,
            n.isActivated AS nActivated,
            t.title,
            t.isInFront,
            t.isActivated AS tActivated
    FROM
        characters c
    LEFT JOIN character_names n ON c.id = n.owner_id
    LEFT JOIN character_titles t ON c.id = t.owner_id
    WHERE
        c.isNewest = 1) r
WHERE
    (r.coloredName IS NULL
        OR r.nActivated = 1)
        AND (r.title IS NULL OR r.tActivated = 1)

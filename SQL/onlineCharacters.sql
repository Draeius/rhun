SELECT 
    r.name, r.gender, r.coloredName, r.title, r.is_in_front
FROM
    (SELECT 
        c.name,
            c.gender,
            n.name AS coloredName,
            n.is_activated AS nActivated,
            t.title,
            t.is_in_front,
            t.is_activated AS tActivated
    FROM
        characters c
    LEFT JOIN character_names n ON c.id = n.owner_id
    LEFT JOIN character_titles t ON c.id = t.owner_id
    WHERE
        c.is_online = 1) r
WHERE
    (r.coloredName IS NULL
        OR r.nActivated = 1)
        AND (r.title IS NULL OR r.tActivated = 1)

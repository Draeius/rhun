
-- Fragt den aktuell ausgewählten Namen und Titel eines Charakters ab
SELECT 
    r.name, r.gender, r.coloredName, r.title, r.isInFront, r.guildTag, r.guildName
FROM
    (SELECT 
        c.name,
            c.gender,
            n.name AS coloredName,
            n.isActivated AS nActivated,
            t.title,
            t.isInFront,
            t.isActivated AS tActivated,
            g.name AS guildName,
            g.tag AS guildTag
    FROM
        characters c
    LEFT JOIN character_names n ON c.id = n.owner_id
    LEFT JOIN character_titles t ON c.id = t.owner_id
    LEFT JOIN guilds g ON g.id = c.guild_id
    WHERE
        c.id = ?) r
WHERE
    (r.coloredName IS NULL
        OR r.nActivated = 1)
        AND (r.title IS NULL OR r.tActivated = 1)
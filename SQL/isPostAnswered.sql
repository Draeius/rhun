
-- Fragt ab, ob ein Post beantwortet wurde.
SELECT 
    l.id AS location_id,
    l.name AS title,
    p1.owner_id AS character_id,
    p1.owner_id != (SELECT -- SELECT den neuesten Post am Ort dieses Posts
            ptest.owner_id
        FROM
            posts ptest
        WHERE
            p1.location_id = ptest.location_id
        ORDER BY ptest.creation_at DESC
        LIMIT 1) AS isAnswered
FROM
    posts p1
        JOIN
    locations l ON p1.location_id = l.id
WHERE
    p1.location_id != 1 -- OOC Posts werden nicht angezeigt
        AND p1.owner_id = ANY (SELECT 
            c.id -- SELECT alle Charaktere eines Users
        FROM
            characters c
        WHERE
            c.account_id = ?)
GROUP BY p1.owner_id , p1.location_id
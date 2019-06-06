
-- Fragt ab, ob ein Post beantwortet wurde.
SELECT 
    l.id AS location_id,
    l.title AS title,
    p1.author_id AS character_id,
    p1.author_id != (SELECT -- SELECT den neuesten Post am Ort dieses Posts
            ptest.author_id
        FROM
            posts ptest
        WHERE
            p1.target_location_id = ptest.target_location_id
        ORDER BY ptest.creationDate DESC
        LIMIT 1) AS isAnswered
FROM
    posts p1
        JOIN
    location l ON p1.target_location_id = l.id
WHERE
    p1.target_location_id != 1 -- OOC Posts werden nicht angezeigt
        AND p1.author_id = ANY (SELECT 
            c.id -- SELECT alle Charaktere eines Users
        FROM
            characters c
        WHERE
            c.account_id = ?)
GROUP BY p1.author_id , p1.target_location_id
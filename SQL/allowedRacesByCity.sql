
-- Holt alle Rassen für eine Stadt

SELECT 
    r.name, r.description, r.city
FROM
    races r
WHERE
    r.allowed = 1 AND r.city = ?
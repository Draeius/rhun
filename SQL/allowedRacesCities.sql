
-- Fragt alle Städte mit erlaubten Rassen ab.

SELECT 
    r.city
FROM
    races r
WHERE
    r.allowed = 1
GROUP BY r.city

SELECT 
    COUNT(p.id) AS count
FROM
    posts p
WHERE
    p.owner_id = ''

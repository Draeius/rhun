
SELECT 
    COUNT(p.id) AS count
FROM
    posts p
WHERE
    p.author_id = ''

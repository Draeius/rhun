SELECT 
    COUNT(c.id) AS count
FROM
    characters c
WHERE
    c.account_id = ?

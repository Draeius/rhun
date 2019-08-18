
SELECT 
    COUNT(p.id) AS count
FROM
    accounts a
        JOIN
    characters c ON c.account_id = a.id
        JOIN
    posts p ON p.owner_id = c.id
WHERE
    a.id = ?

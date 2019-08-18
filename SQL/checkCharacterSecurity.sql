SELECT 
    COUNT(c.id) as result
FROM
    characters c
        JOIN
    accounts a ON c.account_id = a.id
WHERE
    c.id = ? AND a.id = ?;

-- Fragt ab, wie viele neue Nachrichten die Charaktere eines Accounts insgesamt haben.
SELECT 
    COUNT(c.id) AS count
FROM
    characters c
        JOIN
    messages m ON m.addressee_id = c.id
WHERE
    c.account_id = ? AND m.is_old = 0
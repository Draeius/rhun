SELECT 
    u.review_posts >= ?
        AND u.edit_world >= ?
        AND u.write_motd >= ?
        AND u.edit_items >= ?
        AND u.edit_monster >= ? AS result
FROM
    accounts a
        JOIN
    user_levels u ON a.user_level_id = u.id
WHERE
    a.id = ?;

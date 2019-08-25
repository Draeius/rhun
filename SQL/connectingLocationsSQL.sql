SELECT 
    l.id, l.uuid, l.colored_name
FROM
    locations l
WHERE
    l.id IN (SELECT 
            target_location_id
        FROM
            navigations
        WHERE
            location_id = ?)
ORDER BY l.name ASC
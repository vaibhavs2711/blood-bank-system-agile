<?php
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function text_length($value)
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}

function db()
{
    static $connection = null;
    if ($connection === null) {
        require __DIR__ . '/conn.php';
        $connection = $conn;
        if ($connection) {
            mysqli_set_charset($connection, 'utf8mb4');
        }
    }
    return $connection;
}

function page_content($type, $fallback = '')
{
    $connection = db();
    if (!$connection) return $fallback;
    $statement = mysqli_prepare($connection, 'SELECT page_data FROM pages WHERE page_type = ? LIMIT 1');
    if (!$statement) return $fallback;
    mysqli_stmt_bind_param($statement, 's', $type);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($statement);
    return $row['page_data'] ?? $fallback;
}

function blood_groups()
{
    $standard = ['B+', 'B-', 'A+', 'O+', 'O-', 'A-', 'AB+', 'AB-'];
    $connection = db();
    if (!$connection) {
        return array_map(function ($name, $index) { return ['blood_id' => $index + 1, 'blood_group' => $name]; }, $standard, array_keys($standard));
    }
    $result = mysqli_query($connection, 'SELECT blood_id, blood_group FROM blood ORDER BY blood_id');
    if (!$result) {
        return array_map(function ($name, $index) { return ['blood_id' => $index + 1, 'blood_group' => $name]; }, $standard, array_keys($standard));
    }

    $groups = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $existing = array_column($groups, 'blood_group');
    if (array_diff($standard, $existing)) {
        $insert = mysqli_prepare($connection, 'INSERT INTO blood (blood_group) SELECT ? WHERE NOT EXISTS (SELECT 1 FROM blood WHERE blood_group = ?)');
        if ($insert) {
            foreach (array_diff($standard, $existing) as $name) {
                mysqli_stmt_bind_param($insert, 'ss', $name, $name);
                mysqli_stmt_execute($insert);
            }
            mysqli_stmt_close($insert);
            $result = mysqli_query($connection, 'SELECT blood_id, blood_group FROM blood ORDER BY blood_id');
            if ($result) $groups = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }
    return $groups;
}

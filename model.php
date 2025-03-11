<?php

/**
 * Return list of users.
 */
function get_users($conn)
{

   $statement = $conn->query("SELECT a.id, a.name FROM users a INNER JOIN user_accounts b on a.id = b.user_id INNER JOIN transactions t ON (b.id = t.account_from OR b.id = t.account_to) GROUP BY a.id ORDER BY a.id");
   $users = array();
    while ($row = $statement->fetch()) {
        $users[$row['id']] = $row['name'];
    }
   return $users;
}

/**
 * Return transactions balances of given user.
 */
function get_user_transactions_balances($user_id, $conn)
{
    $balances = $conn->query("SELECT y, m, SUM(itog) as balans, SUM(count_tr) AS cnt FROM (
        SELECT STRFTIME('%Y', t.trdate) AS y,
           STRFTIME('%m', t.trdate) AS m,
           SUM(t.amount)*(-1) AS itog,
           COUNT(t.id) as count_tr
      FROM transactions t 
      INNER JOIN user_accounts a ON a.id = t.account_from
      INNER JOIN users u ON u.id = a.user_id
      WHERE u.id = ".$user_id."
      GROUP BY STRFTIME('%Y', t.trdate),
             STRFTIME('%m', t.trdate)  
     UNION ALL 
     SELECT STRFTIME('%Y', t.trdate) AS y,
           STRFTIME('%m', t.trdate) AS m,
           SUM(t.amount) AS itog,
           COUNT(t.id) as count_tr
      FROM transactions t 
      INNER JOIN user_accounts a ON a.id = t.account_to
      INNER JOIN users u ON u.id = a.user_id
      WHERE u.id = ".$user_id."
      GROUP BY STRFTIME('%Y', t.trdate),
             STRFTIME('%m', t.trdate)  
      
      ORDER BY y, m
      )   GROUP BY y,m
    ");
     while ($row = $balances->fetch()) {
        $balans[] = array(
            'year' => $row['y'],
            'month' => $row['m'],
            'balans' => $row['balans'],
            'count' => $row['cnt'],
        );
    }
    return $balans;
}

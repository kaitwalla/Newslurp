<?php

namespace Technical_penguins\Newslurp\Model;

use Technical_penguins\Newslurp\Controller\Database;

class User {
    public static function is_authorized($email_address=false) {
        if (!isset($_SESSION['access']) || !isset($_SESSION['id']) || !isset($_SESSION['email'])) {
            $email_address = isset($_POST['email_address']) ? $_POST['email_address'] :  $email_address;
            if ($email_address) {
                $query = Database::query('SELECT * FROM ' . Database::USER_TABLE . ' WHERE email="' . $email_address . '"');
                $results = $query->fetchAll();
                if (!empty($results)) {
                    if ($results[0]->email == $email_address) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public static function is_installation_activated() {
        if (self::is_authorized()) {
            return true;
        }
        $query = Database::query('SELECT * FROM ' . Database::USER_TABLE);
        $results = $query->fetchAll();
        if (!empty($results)) {
            return true;
        } else {
            return false;
        }
    }

    public static function insert($email, $access, $id, $user_id) {
        $query = Database::query('INSERT INTO ' . Database::USER_TABLE . ' (`email`,`access_token`,`id_token`,`user_id`) VALUES (?,?,?,?)', [$email, $access, $id, $user_id]);
    }

    public static function set_session($email, $access, $id, $user_id) {
        $_SESSION['email'] = $email;
        $_SESSION['access'] = $access;
        $_SESSION['id'] = $id;
        $_SESSION['user_id'] = $user_id;
    }

    public static function update($email, $access, $id, $user_id) {
        if (self::is_installation_activated()) {
            if (self::is_authorized($email)) {
                self::set_session($email, $access, $id, $user_id);
                $query = Database::query('UPDATE ' . Database::USER_TABLE . ' SET access_token=?,id_token=?,user_id=? WHERE email=?', [$access, $id, $user_id, $email]);
            } else {
                return false;
            }
        } else {
            self::insert($email, $access, $id, $user_id);
            self::set_session($email, $access, $id, $user_id);
        }
    }

    public static function update_token($type = 'access', $token) {
        $_SESSION[$type] = $token;
        $query = Database::query('UPDATE ' . Database::USER_TABLE . ' SET ' . $type . '_token=? WHERE email=?',[$token, $_SESSION['email']]);
    }
}
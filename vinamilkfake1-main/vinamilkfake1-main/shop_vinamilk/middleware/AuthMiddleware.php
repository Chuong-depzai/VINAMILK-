<?php
// middleware/AuthMiddleware.php

class AuthMiddleware
{
    /**
     * Kiểm tra user đã đăng nhập chưa
     */
    public static function requireLogin()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để tiếp tục';
            header('Location: index.php?controller=auth&action=showLogin');
            exit;
        }
    }

    /**
     * Kiểm tra user có phải admin không
     */
    public static function requireAdmin()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để tiếp tục';
            header('Location: index.php?controller=auth&action=showLogin');
            exit;
        }

        // Kiểm tra role admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang này';
            header('Location: index.php');
            exit;
        }
    }

    /**
     * Kiểm tra xem user hiện tại có phải admin không
     */
    public static function isAdmin()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    /**
     * Kiểm tra xem user hiện tại đã đăng nhập chưa
     */
    public static function isLoggedIn()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    /**
     * Lấy thông tin user hiện tại
     */
    public static function getCurrentUser()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'phone' => $_SESSION['user_phone'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'] ?? null,
            'role' => $_SESSION['user_role'] ?? 'user',
            'avatar' => $_SESSION['user_avatar'] ?? null
        ];
    }
}

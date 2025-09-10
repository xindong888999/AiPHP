<?php

declare(strict_types=1);

namespace Core\OwnLibrary\Security;

/**
 * CSRF令牌管理器
 * 提供CSRF（跨站请求伪造）防护功能
 * 
 * 该类负责生成、验证和管理CSRF令牌，以防止跨站请求伪造攻击
 * 使用session存储令牌，支持为不同表单生成不同的令牌
 */
class CsrfTokenManager
{
    /**
     * 会话中CSRF令牌的存储键名
     */
    private const SESSION_KEY = '_csrf_tokens';

    /**
     * CSRF令牌的默认有效期（秒）
     */
    private const DEFAULT_EXPIRATION = 3600; // 1小时

    /**
     * 初始化CSRF管理器
     * 确保会话已启动
     */
    public function __construct()
    {
        $this->ensureSessionStarted();
    }

    /**
     * 确保会话已启动
     * 如果会话未启动，则启动会话
     */
    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * 生成新的CSRF令牌
     * 
     * @param string $tokenName 令牌名称，用于区分不同表单的令牌
     * @return string 生成的CSRF令牌
     */
    public function generateToken(string $tokenName = 'default'): string
    {
        // 确保令牌存储数组已初始化
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }

        // 生成随机令牌（使用openssl确保安全性）
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $expiration = time() + self::DEFAULT_EXPIRATION;

        // 存储令牌和过期时间
        $_SESSION[self::SESSION_KEY][$tokenName] = [
            'token' => $token,
            'expires_at' => $expiration
        ];

        return $token;
    }

    /**
     * 验证CSRF令牌
     * 
     * @param string $token 待验证的令牌
     * @param string $tokenName 令牌名称
     * @param bool $regenerate 验证后是否重新生成令牌
     * @return bool 验证结果
     */
    public function validateToken(string $token, string $tokenName = 'default', bool $regenerate = true): bool
    {
        // 检查令牌存储和指定的令牌是否存在
        if (!isset($_SESSION[self::SESSION_KEY]) || !isset($_SESSION[self::SESSION_KEY][$tokenName])) {
            error_log("CSRF验证失败: 令牌存储或指定令牌不存在");
            return false;
        }

        $storedToken = $_SESSION[self::SESSION_KEY][$tokenName];
        $currentTime = time();

        // 检查令牌是否过期
        if ($currentTime > $storedToken['expires_at']) {
            // 删除过期令牌
            unset($_SESSION[self::SESSION_KEY][$tokenName]);
            error_log("CSRF验证失败: 令牌已过期");
            return false;
        }

        // 比较令牌
        $isValid = hash_equals($storedToken['token'], $token);
        
        if (!$isValid) {
            error_log("CSRF验证失败: 令牌不匹配");
            error_log("存储的令牌: " . $storedToken['token']);
            error_log("提供的令牌: " . $token);
        }

        // 如果验证成功且需要重新生成，则生成新令牌
        if ($isValid && $regenerate) {
            $this->generateToken($tokenName);
        }

        return $isValid;
    }

    /**
     * 获取当前的CSRF令牌
     * 如果令牌不存在或已过期，则生成新令牌
     * 
     * @param string $tokenName 令牌名称
     * @return string 当前有效的CSRF令牌
     */
    public function getToken(string $tokenName = 'default'): string
    {
        // 检查令牌存储和指定的令牌是否存在
        if (isset($_SESSION[self::SESSION_KEY]) && isset($_SESSION[self::SESSION_KEY][$tokenName])) {
            $storedToken = $_SESSION[self::SESSION_KEY][$tokenName];
            $currentTime = time();

            // 检查令牌是否即将过期（剩余时间小于5分钟）
            if ($currentTime < $storedToken['expires_at'] - 300) {
                return $storedToken['token'];
            }
        }

        // 令牌不存在或即将过期，生成新令牌
        return $this->generateToken($tokenName);
    }

    /**
     * 生成包含CSRF令牌的HTML隐藏字段
     * 
     * @param string $tokenName 令牌名称
     * @param string $fieldName 表单字段名称
     * @return string HTML隐藏字段
     */
    public function getTokenField(string $tokenName = 'default', string $fieldName = '_csrf_token'): string
    {
        $token = $this->getToken($tokenName);
        return '<input type="hidden" name="' . htmlspecialchars($fieldName) . '" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * 删除指定的CSRF令牌
     * 
     * @param string $tokenName 令牌名称
     */
    public function removeToken(string $tokenName = 'default'): void
    {
        if (isset($_SESSION[self::SESSION_KEY]) && isset($_SESSION[self::SESSION_KEY][$tokenName])) {
            unset($_SESSION[self::SESSION_KEY][$tokenName]);
        }
    }

    /**
     * 清理所有过期的CSRF令牌
     */
    public function cleanupExpiredTokens(): void
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            return;
        }

        $currentTime = time();
        foreach ($_SESSION[self::SESSION_KEY] as $tokenName => $tokenData) {
            if ($currentTime > $tokenData['expires_at']) {
                unset($_SESSION[self::SESSION_KEY][$tokenName]);
            }
        }
    }

    /**
     * 验证请求中的CSRF令牌
     * 自动从$_POST或HTTP头中获取令牌
     * 
     * @param string $tokenName 令牌名称
     * @param string $fieldName 表单字段名称
     * @param string $headerName HTTP头名称
     * @return bool 验证结果
     */
    public function validateRequestToken(
        string $tokenName = 'default', 
        string $fieldName = '_csrf_token', 
        string $headerName = 'X-CSRF-Token'
    ): bool {
        // 从POST数据中获取令牌
        $token = $_POST[$fieldName] ?? null;

        // 如果POST中没有，则从HTTP头中获取
        if (!$token && isset($_SERVER['HTTP_' . strtoupper(str_replace('-', '_', $headerName))])) {
            $token = $_SERVER['HTTP_' . strtoupper(str_replace('-', '_', $headerName))];
        }
        
        // 如果仍然没有令牌，尝试从JSON请求体中获取
        if (!$token) {
            $input = file_get_contents('php://input');
            if (!empty($input)) {
                $data = json_decode($input, true);
                if (isset($data[$fieldName])) {
                    $token = $data[$fieldName];
                }
            }
        }
        
        // 如果令牌不存在，验证失败
        if (!$token) {
            return false;
        }

        return $this->validateToken($token, $tokenName);
    }
    
    /**
     * 生成包含CSRF令牌的meta标签
     * 
     * @param string $tokenName 令牌名称
     * @return string HTML meta标签
     */
    public function getMetaTag(string $tokenName = 'default'): string
    {
        $token = $this->getToken($tokenName);
        return '<meta name="csrf-token" content="' . htmlspecialchars($token) . '">';
    }
}
<?php
// 设置HTTP状态码为404
http_response_code(404);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>页面未找到 - 404错误</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Microsoft YaHei', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            max-width: 90%;
            width: 500px;
        }

        .error-code {
            font-size: 80px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 20px;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
        }

        .error-title {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }

        .error-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .back-link {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            min-width: 120px;
        }

        .back-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .back-link:active {
            transform: translateY(0);
        }

        .back-button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            min-width: 120px;
            border: none;
            cursor: pointer;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .back-button:active {
            transform: translateY(0);
        }

        .astronaut {
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="40" r="20" fill="%23667eea"/><circle cx="40" cy="35" r="3" fill="white"/><circle cx="60" cy="35" r="3" fill="white"/><path d="M45 45 Q50 50 55 45" stroke="white" stroke-width="2" fill="none"/><rect x="30" y="60" width="40" height="30" rx="5" fill="%23764ba2"/><rect x="25" y="65" width="15" height="20" rx="5" fill="%23667eea"/><rect x="60" y="65" width="15" height="20" rx="5" fill="%23667eea"/></svg>') no-repeat center;
            background-size: contain;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }
            
            .error-code {
                font-size: 60px;
            }
            
            .error-title {
                font-size: 20px;
            }
            
            .button-group {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="astronaut"></div>
        <div class="error-code">404</div>
        <h1 class="error-title">页面未找到</h1>
        <p class="error-message">抱歉，您访问的页面不存在或已被移除。<br>请检查URL是否正确，或返回首页继续浏览。</p>
        <div class="button-group">
            <button class="back-button" onclick="history.back()">退回</button>
            <a href="/" class="back-link">返回首页</a>
        </div>
    </div>
</body>
</html>
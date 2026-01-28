<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Your GoAustralia OTP Code</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            background-color: #F2F6FF; 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            margin: 0; 
            padding: 0; 
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        
        .container {
            width: 100%;
            margin: 0;
            background-color: #F2F6FF;
        }
        
        .header {
            background-color: #00246B;
            padding: 40px 20px;
            text-align: center;
        }
        
        .header img {
            max-width: 200px;
            height: auto;
            margin-bottom: 16px;
        }
        
        .header h1 {
            color: #F2F6FF;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .header p {
            color: #CADCFC;
            font-size: 18px;
        }
        
        .main {
            padding: 48px 20px;
        }
        
        .content-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            padding: 48px;
            text-align: center;
        }
        
        .intro h1 {
            color: #00246B;
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 16px;
            line-height: 2.5rem;
        }
        
        .intro p {
            color: #7692C9;
            font-size: 18px;
            margin-bottom: 32px;
        }
        
        .otp-container {
            background-color: #F8F9FA;
            border: 2px dashed #CADCFC;
            border-radius: 12px;
            padding: 32px;
            margin: 32px 0;
        }
        
        .otp-label {
            color: #00246B;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        
        .otp-code {
            color: #FF6C00;
            font-size: 48px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            letter-spacing: 8px;
            margin: 16px 0;
        }
        
        .expiry-notice {
            background-color: #FFF3E0;
            border-left: 4px solid #FF6C00;
            padding: 16px;
            margin: 24px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .expiry-notice p {
            color: #00246B;
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        
        .security-notice {
            background-color: #E3F2FD;
            border-radius: 8px;
            padding: 20px;
            margin-top: 32px;
        }
        
        .security-notice h3 {
            color: #00246B;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .security-notice p {
            color: #7692C9;
            font-size: 14px;
            margin: 0;
        }
        
        .footer {
            background-color: #00246B;
            color: #F2F6FF;
            padding: 32px 20px;
            text-align: center;
            margin-top: 48px;
        }
        
        .footer p {
            margin-bottom: 16px;
        }
        
        @media only screen and (max-width: 600px) {
            .content-container {
                margin: 0 10px;
                padding: 32px 24px;
            }
            
            .intro h1 {
                font-size: 1.75rem;
                line-height: 2rem;
            }
            
            .intro p {
                font-size: 16px;
            }
            
            .otp-code {
                font-size: 36px;
                letter-spacing: 4px;
            }
            
            .header img {
                max-width: 150px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .header p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ config('constants.logo') }}" alt="GoAustralia Logo">
            <p>Your Journey to Australia Starts Here</p>
        </div>

        <div class="main">
            <div class="content-container">
                <div class="intro">
                    <h1>Your OTP Code</h1>
                    <p>Use this code to verify your email address and continue with GoAustralia</p>
                </div>

                <div class="otp-container">
                    <div class="otp-label">Verification Code</div>
                    <div class="otp-code">{{ $otpCode }}</div>
                </div>

                <div class="expiry-notice">
                    <p>⏰ This code will expire in 5 minutes</p>
                </div>

                <div class="security-notice">
                    <h3>🔒 Security Notice</h3>
                    <p>Never share this code with anyone. GoAustralia will never ask for your OTP code via phone, email, or any other method.</p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} GoAustralia. All rights reserved.</p>
            <p>If you didn't request this code, please ignore this email.</p>
        </div>
    </div>
</body>
</html>

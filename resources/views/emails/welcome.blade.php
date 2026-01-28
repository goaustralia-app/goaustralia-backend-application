<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to GoAustralia</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2E8B57, #228B22);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 30px;
        }
        .feature {
            background: #f8f9fa;
            border-left: 4px solid #2E8B57;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .feature h3 {
            margin: 0 0 10px 0;
            color: #2E8B57;
            font-size: 18px;
        }
        .feature p {
            margin: 0;
            color: #666;
        }
        .cta {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            background: #2E8B57;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .australian-flag {
            width: 40px;
            height: auto;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🇦🇺 Welcome to GoAustralia!</h1>
            <p>Your Australian Immigration Journey Starts Here</p>
        </div>
        
        <div class="content">
            <h2>G'day {{ $user->name }}!</h2>
            
            <p>Welcome to GoAustralia, your trusted partner in navigating the Australian immigration process. We're thrilled to have you join thousands of successful migrants who have made Australia their new home.</p>
            
            <h3 style="color: #2E8B57; margin-top: 30px;">🚀 Your Immigration Features at a Glance:</h3>
            
            <div class="feature">
                <h3>📋 Track Your Expression of Interest (EOI)</h3>
                <p>Monitor your SkillSelect EOI status in real-time, get notifications when invited, and track processing times for your nominated occupation.</p>
            </div>
            
            <div class="feature">
                <h3>📄 Visa Application Management</h3>
                <p>Complete step-by-step guidance for all visa categories including Skilled Independent (189), Skilled Nominated (190), and Skilled Regional (491) visas.</p>
            </div>
            
            <div class="feature">
                <h3>🎯 Skills Assessment Tracker</h3>
                <p>Track your skills assessment progress with relevant authorities (Engineers Australia, ACS, VETASSESS, and more).</p>
            </div>
            
            <div class="feature">
                <h3>🌟 Points Calculator</h3>
                <p>Real-time points calculation for the Australian Points Test, with suggestions to maximize your score.</p>
            </div>
            
            <div class="feature">
                <h3>📚 Document Checklist</h3>
                <p>Comprehensive document requirements for each visa type, with upload tracking and verification status.</p>
            </div>
            
            <div class="feature">
                <h3>🏥 Health & Character Check Manager</h3>
                <p>Schedule and track your health examinations and police clearances from all countries you've lived in.</p>
            </div>
            
            <div class="feature">
                <h3>💰 Cost Estimator</h3>
                <p>Transparent breakdown of all government fees, assessment costs, and professional charges.</p>
            </div>
            
            <div class="feature">
                <h3>📞 Expert Support</h3>
                <p>Access to registered migration agents and immigration lawyers for personalized guidance.</p>
            </div>
            
            <div class="cta">
                <a href="{{ config('app.url') }}/dashboard" class="btn">Start Your Immigration Journey</a>
            </div>
            
            <p><strong>What's Next?</strong></p>
            <ol>
                <li>Complete your profile with personal and professional details</li>
                <li>Take our eligibility assessment to find the best visa options</li>
                <li>Begin tracking your EOI and skills assessment</li>
                <li>Follow your personalized immigration roadmap</li>
            </ol>
            
            <p>Remember, immigrating to Australia is a significant journey, but you're not alone. Our platform is designed to guide you through every step, from initial planning to receiving your Australian visa.</p>
            
            <p>If you have any questions, our support team is here to help 24/7.</p>
            
            <p>Best regards,<br>
            <strong>The GoAustralia Team</strong><br>
            Making Australian Dreams Come True 🇦🇺</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} GoAustralia. All rights reserved.</p>
            <p>This email was sent to {{ $user->email }}. Need help? Contact us at support@goaustralia.com</p>
        </div>
    </div>
</body>
</html>
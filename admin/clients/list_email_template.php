<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
           
        }
        .header {
            padding: 10px 0;
            text-align: left;
        }
        .content {
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 0.9em;
        }
        h6{
            font-size: 16px;
            font-family: DMSans;
            font-weight: 600;
            margin-bottom: 0;
            text-transform: capitalize;
        }
        h4 a {
            font-size: 20px;
            font-family: DMSans;
            font-weight: 700;
            color: #016492;
            margin-bottom: 0;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            display: -webkit-box;
            overflow: hidden;
            text-transform: capitalize;
        }
        hr {
            margin: 1rem 0;
            color: inherit;
            opacity: .25;
        }

        h6 span {
            font-weight: 400;
            text-transform: capitalize;
        }

        .btn {
            height: unset;
            width: unset;
            color: #222;
            padding: 8px;
            border: 1px solid #222;
            font-weight: 600;
            border-radius: 0;
            font-family: Arial, sans-serif;
        }

        strong{
            color:#cb192d;
        }

    
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{HOME_URL}}"><img src="{{ADMIN_URL}}/assets/images/mail_logo.jpg" width="100px"></a>
        </div>
      
        <div class="content">
            <p><b>Dear M/s. {{company_name}}</b></p>
            <p>Greetings from <a href="www.Tender18.com">www.Tender18.com</a></p>
            <p>We found following New Tenders related to your Product & Services.</p>
            <p>The list of Today’s New Tenders - <b>{{cdate}}</b> is provided below.</p>
                    
                {{ar}}

            <p>For any further assistance, email us at <a href="support@tender18.in">support@tender18.in</a> or contact our custom support on below Numbers.</p>
            <p>Warm regards,</p>
            <p>Customer Support<br><b>Tender18 Infotech Private Limited</b><br>B-502, Sivanta One, Opp. Bank of Baroda, Pritam Nagar, Paldi, Ahmedabad-380007, Gujarat</p>
            <p>Customer Care Number: +91 90819 00466 | +91 74340 61818</p>
            <p>Emai id: <a href="mailto:support@tender18.in">support@tender18.in</a> | <a href="mailto:sales@tender18.in">sales@tender18.in</a></p>
            <p>Website: <a href="www.Tender18.com">www.Tender18.com</a></p>
            <p><b>Disclaimer:</b></p>
            <p>You are receiving this communication because you have subscribed to tender alerts from TENDER18 Infotech Private Limited. As part of our service, we send relevant government tender opportunities directly to your registered email. If you no longer wish to receive these notifications, you may opt out at any time by clicking unsubscribe or by replying to this email with "Unsubscribe" in the subject line.</p>
        </div>
    </div>
</body>
</html>
